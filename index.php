<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrape</title>
    <style>
        * {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            box-sizing: border-box;
        }

        body {
            padding: 0;
            margin: 0;
        }

        main {
            max-width: 100ch;
            padding:1rem 2rem;
            margin: 0 auto;
            background-color: snow;
            box-shadow: 0px 10px 10px 0px #E5E5E5;
        }
    </style>
</head>

<body>
    <main>
        <h1>Finn læringsutbytte</h1>
        <p>Lim inn link til emnebeskrivelsen under.</p>
        <form action="" method="post">
            <label for="inp1">Link: </label>
            <input id="inp1" type="text" name="link" />
            <button id="btn1" type="submit">Send inn</button>
        </form>
        <?php
        // URL of the webpage you want to scrape
        //$url = 'https://www.uia.no/studier/emner/2024/host/is-115.html';
        //$url = 'https://www.uia.no/studier/emner/2024/host/be-111.html';
        if (isset($_POST["link"])) {
            $url = $_POST["link"];
            // Initialize a cURL session
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute the cURL session and fetch the webpage content
            $html = curl_exec($ch);
            curl_close($ch);

            // Check if the content was fetched successfully
            if ($html === false) {
                die('Error fetching the webpage content.');
            }

            // Load the HTML content into a DOMDocument
            $dom = new DOMDocument();
            @$dom->loadHTML($html);

            // Create a new DOMXPath object
            $xpath = new DOMXPath($dom);

            // Query the DOM to find specific elements (e.g., all <a> tags)
            $h1 = $xpath->query('//h1');

            echo "<h1>" . $h1->item(0)->textContent . "</h1>";
            echo "<a href=" . $_POST["link"] . " target=_blank>Se hele emnebeskrivelsen.</a>";
            // Se etter innhold
            /* $innhold = $xpath->query('//h2');
        foreach ($innhold as $h2) {
        
            // Hvis tittelen er innhold
            if ($h2->textContent === "Innhold") {
                echo "<pre>";
                print_r($h2);
                echo "</pre><br>";
        
                $sibling = $h2->nextElementSibling;
        
                echo "Sibling <br>";
                echo "<pre>";
                print_r($sibling);
                echo "</pre><br>";
        
                echo "<h2>Innhold</h2>";
                echo $sibling->textContent;
            }
        } */

            // Find the <h2> header with the text "Innhold"
            $innholdHeader = $xpath->query('//h2[text()="Læringsutbytte"]')->item(0);

            // Initialize an array to hold the nodes between the headers
            $nodesBetween = [];

            // Check if the "Innhold" header was found
            if ($innholdHeader) {
                // Loop through the siblings of the "Innhold" header until the next <h2> header is found
                for ($node = $innholdHeader->nextSibling; $node; $node = $node->nextSibling) {
                    if ($node->nodeType === XML_ELEMENT_NODE || $node->nodeType === XML_TEXT_NODE) {
                        if ($node->nodeName === 'h2') {
                            break; // Stop if the next <h2> header is found
                        }
                        $nodesBetween[] = $dom->saveHTML($node);
                    }
                }
            }

            // Output the HTML content between the "Innhold" header and the next <h2> header
            echo "<h2>Læringsutbytte</h2>";
            echo implode('', $nodesBetween);
        }

        ?>
    </main>
</body>

</html>