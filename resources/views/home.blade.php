<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ env('APP_NAME') }}</title>
        <style type="text/css">
            body {
                font-size: 1.25rem;
                background: tomato;
            }
            header {
                text-align: center;
            }
            #container {
                padding-left: 0 0.25em;
            }
            h1 {
                font-size: 2.5em;
                background: greenyellow;
                border-radius: 25px;
                display: inline;
                padding: 0 0.25em;
            }
            h2 {
                font-size: 1.5em;
                font-family: sans-serif;
                font-style: italic;
            }
            pre {
               white-space: pre-wrap;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <header>
                <h1>Quiche Holder</h1>
            </header>

            <h2>Usage</h2>
            <pre>GET https://quiche-holder.briandady.com/{width}/{height}</pre>

            <h2>Example</h2>
            <pre>&#x3C;img src=&#x22;https://quiche-holder.briandady.com/350/250&#x22; alt=&#x22;Professional photo of a delicious quiche&#x22;&#x3E;</pre>
            <img src="https://quiche-holder.briandady.com/350/250" alt="Professional photo of a delicious quiche">
        </div>
    </body>
</html>