<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    /** Pages of /discover/movie fetched for the favorite genres. */
    private const GENRE_PAGES = 3;

    private const MIN_VOTE_COUNT = 100;
    private const LIMIT = 100;
    private const CACHE_TTL = 600;

    public function __construct(private TmdbService $tmdb)
    {
    }

    /**
     * Movies to recommend: from the favorite genres and from the favorite actors' filmography,
     * with at least one favorite genre (no genre filter when the user has none), without
     * favorites/dislikes or duplicates, newest first.
     *
     * The result is cached per set of inputs, so it refreshes as soon as the user changes
     * their genres, actors, favorites or dislikes.
     *
     * @param  array<int>  $likedGenreIds
     * @param  array<int>  $actorIds
     * @param  array<int>  $excludedIds  ids of favorite and disliked movies
     * @return array<int, array>
     */
    public function forPreferences(array $likedGenreIds, array $actorIds, array $excludedIds): array
    {
        $likedGenreIds = $this->sortedInts($likedGenreIds);
        $actorIds = $this->sortedInts($actorIds);
        $excludedIds = $this->sortedInts($excludedIds);

        $key = 'recommendations:'.md5(json_encode([$likedGenreIds, $actorIds, $excludedIds]));

        return Cache::remember($key, self::CACHE_TTL, fn () => $this->build($likedGenreIds, $actorIds, $excludedIds));
    }

    private function build(array $likedGenreIds, array $actorIds, array $excludedIds): array
    {
        $excluded = array_flip($excludedIds);

        return $this->candidates($likedGenreIds, $actorIds)
            ->filter(fn ($movie) => ($movie['media_type'] ?? 'movie') === 'movie'
                && ! empty($movie['backdrop_path'])
                && ! empty($movie['poster_path'])
                && ($movie['vote_count'] ?? 0) > self::MIN_VOTE_COUNT
                && ! empty($movie['genre_ids'])
                && (! $likedGenreIds || array_intersect($movie['genre_ids'], $likedGenreIds))
                && ! isset($excluded[(int) $movie['id']]))
            ->unique('id')
            ->sortByDesc(fn ($movie) => $movie['release_date'] ?? '')
            ->take(self::LIMIT)
            ->values()
            ->all();
    }

    private function candidates(array $likedGenreIds, array $actorIds): Collection
    {
        $requests = [];

        if ($likedGenreIds) {
            for ($page = 1; $page <= self::GENRE_PAGES; $page++) {
                $requests["genres-$page"] = ['/discover/movie', [
                    'language' => 'en-US',
                    'include_adult' => 'false',
                    'sort_by' => 'popularity.desc',
                    'vote_count.gte' => self::MIN_VOTE_COUNT,
                    'with_genres' => implode('|', $likedGenreIds),
                    'page' => $page,
                ]];
            }
        }

        foreach ($actorIds as $actorId) {
            $requests["actor-$actorId"] = ["/person/$actorId/combined_credits", ['language' => 'en-US']];
        }

        return collect($this->tmdb->getMany($requests))
            ->map(fn ($response, $name) => str_starts_with($name, 'genres-')
                ? ($response['results'] ?? [])
                : ($response['cast'] ?? []))
            ->flatten(1);
    }

    /** @return array<int> */
    private function sortedInts(array $ids): array
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        sort($ids);

        return $ids;
    }
}
