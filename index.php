<?php
require_once("product.php");
require_once("category.php");

function searchByName($products, $name) {
    foreach ($products as $product) {
        if ($product->name == $name) {
            return $product;
        }
    }
    return null;
}

function searchCategory($categories, $name) {
    foreach ($categories as $category) {
        if ($category->name == $name) {
            return $category;
        }
    }
    return null;
}

session_start();

if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}

if (!isset($_SESSION['categories'])) {
    $_SESSION['categories'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_name']) && isset($_POST['price']) && isset($_POST['action']) && $_POST['action'] == 'addProduct') {
    $name = $_POST['product_name'];
    $price = $_POST['price'];

    $newProduct = new Product($name, $price);
    array_push($_SESSION['products'], $newProduct);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name']) && isset($_POST['action']) && $_POST['action'] == 'addCategory') {
    $name = $_POST['category_name'];

    $newCategory = new Category($name, $_SESSION['products']);
    array_push($_SESSION['categories'], $newCategory);
    $_SESSION['products'] = [];
}

$searchResult = null;
$categorySearchResult = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_name']) && isset($_POST['action']) && $_POST['action'] == 'searchProduct') {
    $searchName = $_POST['search_name'];
    $searchResult = searchByName($_SESSION['products'], $searchName);
}

$products = $_SESSION['products'];
$categories = $_SESSION['categories'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Товари</title>
</head>
<body>
    <h2>Додати товар</h2>
    <form method="post" action="">
        <input type="hidden" name="action" value="addProduct">
        <label for="product_name">Імʼя:</label>
        <input type="text" id="product_name" name="product_name" required>
        <br>
        <label for="price">Ціна:</label>
        <input type="text" id="price" name="price" required>
        <br>
        <br>
        <button type="submit">Додати</button>
    </form>

    <h2>Додати категорію</h2>
    <form method="post" action="">
        <input type="hidden" name="action" value="addCategory">
        <label for="category_name">Імʼя:</label>
        <input type="text" id="category_name" name="category_name" required>
        <br>
        <br>
        <button type="submit">Додати</button>
    </form>

    <h2>Пошук товару</h2>
    <form method="post" action="">
        <input type="hidden" name="action" value="searchProduct">
        <label for="search_name">Імʼя:</label>
        <input type="text" id="search_name" name="search_name" required>
        <br>
        <br>
        <button type="submit">Шукати</button>
    </form>

    <?php
        echo "<h3>Результат пошуку</h3>";
        if ($searchResult !== null) {
            echo "<p>" . $searchResult->getProduct() . "</p>";
        } elseif (isset($_POST['search_name'])) {
            echo "<p>Товар не знайдено.</p>";
        }
    ?>

    <h2>Вивод всіх товарів</h2>
    <ul>
        <?php
        foreach ($products as $product) {
            echo "<li>" . $product->getProduct() . "</li>";
        }
        ?>
    </ul>

    <h2>Категорії</h2>
    <ul id="category-list">
        <?php
        foreach ($categories as $category) {
            echo "<li><a href='#' onclick='showCategoryProducts(\"" . $category->getCategoryName() . "\")'>" . $category->getCategoryName() . "</a></li>";
        }
        ?>
    </ul>

    <h2>Продукти у вибраній категорії</h2>
    <div id="category-products">
        <p>Виберіть категорію, щоб побачити продукти.</p>
    </div>

    <script>
        function showCategoryProducts(categoryName) {
            let categories = <?php echo json_encode($categories); ?>;
            let categoryProductsDiv = document.getElementById('category-products');
            categoryProductsDiv.innerHTML = '';

            for (let i = 0; i < categories.length; i++) {
                if (categories[i].name === categoryName) {
                    let products = categories[i].list_products;
                    let productList = '<ul>';
                    for (let j = 0; j < products.length; j++) {
                        productList += '<li>' + products[j].name + ' - ' + products[j].price + '</li>';
                    }
                    productList += '</ul>';
                    categoryProductsDiv.innerHTML = productList;
                    break;
                }
            }
        }
    </script>
</body>
</html>