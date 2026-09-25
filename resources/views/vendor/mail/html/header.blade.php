<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Filments')
<img src="{{asset('img/logo-filments.svg')}}" alt="{{config('app.name')}}" class="logo" width="200" style="width: 200px; height: auto;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
