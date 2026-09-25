<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="https://filments.zamarareyes.es/img/favicon.svg">
        <link rel="stylesheet" href="https://filments.zamarareyes.es/css/app.css">
        <link rel="stylesheet" href="https://filments.zamarareyes.es/css/tailwind.min.css">
        <link rel="stylesheet" href="https://filments.zamarareyes.es/css/components.min.css">
        <link rel="stylesheet" href="https://filments.zamarareyes.es/css/utilities.min.css">
        <link rel="stylesheet" href="https://filments.zamarareyes.es/css/fontawesome-all.min.css">
        <link rel="stylesheet" href="https://filments.zamarareyes.es/css/owl.carousel.min.css">
        <title>Error 404</title>

        <style>
            /* vietnamese */
            @font-face {
                font-family: 'Josefin Sans';
                font-style: normal;
                font-weight: 400;
                src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUAnx4RHw.woff2) format('woff2');
                unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
            }

            /* latin-ext */
            @font-face {
                font-family: 'Josefin Sans';
                font-style: normal;
                font-weight: 400;
                src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUA3x4RHw.woff2) format('woff2');
                unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
            }

            /* latin */
            @font-face {
                font-family: 'Josefin Sans';
                font-style: normal;
                font-weight: 400;
                src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUDXx4.woff2) format('woff2');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }

            /* vietnamese */
            @font-face {
                font-family: 'Josefin Sans';
                font-style: normal;
                font-weight: 700;
                src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUAnx4RHw.woff2) format('woff2');
                unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
            }

            /* latin-ext */
            @font-face {
                font-family: 'Josefin Sans';
                font-style: normal;
                font-weight: 700;
                src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUA3x4RHw.woff2) format('woff2');
                unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
            }

            /* latin */
            @font-face {
                font-family: 'Josefin Sans';
                font-style: normal;
                font-weight: 700;
                src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUDXx4.woff2) format('woff2');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }


            * {
                -webkit-box-sizing: border-box;
                box-sizing: border-box
            }

            body {
                padding: 0;
                margin: 0
            }

            @media (max-width: 30rem) {
              h1 {
                font-size: 8.5rem;
              }
            }
            h1 > span {
              -webkit-animation: spooky 2s alternate infinite linear;
                      animation: spooky 2s alternate infinite linear;
              color: #528cce;
              display: inline-block;
            }

            h2 {
              color: #e7ebf2;
              margin-bottom: 0.4em;
            }

            p {
              color: #ccc;
              margin-top: 0;
            }

            @-webkit-keyframes spooky {
              from {
                transform: translatey(0.15em) scaley(0.95);
              }
              to {
                transform: translatey(-0.15em);
              }
            }

            @keyframes spooky {
              from {
                transform: translatey(0.15em) scaley(0.95);
              }
              to {
                transform: translatey(-0.15em);
              }
            }

            #notfound {
                position: relative;
                height: 100vh;
                background-color: rgba(17,24,39,1);
            }

            #notfound .notfound {
                position: absolute;
                left: 50%;
                top: 50%;
                -webkit-transform: translate(-50%, -50%);
                -ms-transform: translate(-50%, -50%);
                transform: translate(-50%, -50%)
            }

            .notfound {
                max-width: 460px;
                width: 100%;
                text-align: center;
                line-height: 1.4
            }

            .notfound .notfound-404 {
                height: 158px;
                line-height: 153px
            }

            .notfound .notfound-404 h1 {
                font-family: josefin sans, sans-serif;
                color: #222;
                font-size: 220px;
                letter-spacing: 10px;
                margin: 0;
                font-weight: 700;
                text-shadow: 2px 2px 0 #c9c9c9, -2px -2px 0 #c9c9c9
            }

            .notfound .notfound-404 h1>span {
                text-shadow: 2px 2px 0 #ffab00, -2px -2px 0 #ffab00, 0 0 8px #ff8700
            }

            .notfound p {
                font-family: MontserratRegular, sans-serif;
                color: #fff;
                font-size: 16px;
                font-weight: 400;
                margin-top: 0;
                margin-bottom: 15px
            }

            .notfound a {
                -webkit-transition: .2s all;
                transition: .2s all
            }

            .notfound a:hover {
                color: #ffab00;
                border-color: #ffab00
            }

            @media only screen and (max-width:480px) {
                .notfound .notfound-404 {
                    height: 122px;
                    line-height: 122px
                }

                .notfound .notfound-404 h1 {
                    font-size: 122px
                }
            }

        </style>

    </head>

    <body class="bg-gray-900">
        <div id="notfound">
            <div class="notfound">
                <h1 class="text-white font-bold text-9xl mb-4">4<span><i class="fas fa-ghost text-yellow-500 mx-4"></i></span>4</h1>
                <p class="text-gray-500">The page you are looking for might have been removed had its name changed or is temporarily unavailable.
                </p>
                <a href="/" class="bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-4 md:px-6 rounded-full text-sm font-400 mr-a block text-center inline-block mt-4">Go to home</a>
            </div>
        </div>
    </body>

</html>