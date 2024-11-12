<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RecipeDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle ingredient search
$ingredient = isset($_GET['ingredient']) ? $_GET['ingredient'] : '';
$recipes = [];

if ($ingredient) {
    $sql = "SELECT * FROM recipes WHERE ingredients LIKE ?";
    $stmt = $conn->prepare($sql);
    $search = "%$ingredient%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Recommendation Engine</title>
    <style>
        /* Background styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                        url('https://source.unsplash.com/1600x900/?kitchen,food') no-repeat center center;
            background-size: cover;
            color: #ffffff;
        }

        /* Container styling */
        .container {
            max-width: 500px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            backdrop-filter: blur(8px);
        }

        h1 {
            font-size: 2em;
            margin-bottom: 0.5em;
            color: #FFEB3B;
        }

        /* Input and button styling */
        label, input[type="text"], button {
            display: block;
            width: 100%;
            font-size: 1em;
            margin-top: 10px;
        }

        label {
            color: #FFEB3B;
            font-weight: bold;
        }

        input[type="text"] {
            padding: 12px;
            border: 2px solid #FFEB3B;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #333;
        }

        button {
            background-color: #66bb6a;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 15px;
        }

        button:hover {
            background-color: #4CAF50;
        }

        /* Recipe card styling */
        .recipe {
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            text-align: left;
            color: #FFF;
            transition: transform 0.2s;
        }

        .recipe h3 {
            color: #FFEB3B;
            font-size: 1.5em;
            margin: 0 0 5px;
        }

        .recipe p {
            color: #ffffff;
            margin: 0;
            line-height: 1.6;
        }

        .recipe:hover {
            transform: scale(1.02);
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                width: 90%;
            }
            h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recipe Recommendation Engine</h1>
        <form method="GET">
            <label for="ingredient">Enter an ingredient:</label>
            <input type="text" name="ingredient" id="ingredient" placeholder="e.g., eggs, flour">
            <button type="submit">Find Recipes</button>
        </form>
        
        <h2>Recommended Recipes:</h2>
        <?php if ($ingredient && count($recipes) > 0): ?>
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe">
                    <h3><?php echo htmlspecialchars($recipe['name']); ?></h3>
                    <p>Ingredients: <?php echo htmlspecialchars($recipe['ingredients']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php elseif ($ingredient): ?>
            <p>No recipes found for "<?php echo htmlspecialchars($ingredient); ?>"</p>
        <?php endif; ?>
    </div>
</body>
</html>
