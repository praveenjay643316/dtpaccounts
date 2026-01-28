<?php
require_once __DIR__ . '/../config/configPublic.php';

class preSet_401
{
    use siteInfo;
    use CommonFunctions;
}
$preSet_401 = new preSet_401();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 Unauthorized</title>
    <style>
        html {
            font-family: Arial, sans-serif;
            color: #000;
            font-size: 16px;
            font-weight: 400;
        }

        main {
            margin: 5rem 0 0 5rem;
        }

        h1,
        p {
            margin-top: 0;
            margin-bottom: 2rem;
        }

        h1 {
            font-weight: 700;
            font-size: 4.5rem;
        }

        p {
            color: #7d7d7d;
            font-size: 1.75rem;
        }

        @media screen and (max-width: 768px) {
            html {
                font-size: 12px;
            }

            main {
                margin: 3rem 0 0 3rem;
            }
        }
    </style>
</head>

<body>
    <main>
        <h1>401 Unauthorized</h1>
        <p>
        Unauthorized / Session Expired, <a href="<?php echo htmlentities($preSet_401->siteData()->website_url); ?>">Click here to Login again</a>  
        </p>
    </main>
</body>

</html>
