<?php
// include database and object file
include_once "config/database.php";
include_once "objects/product.php";
include_once "objects/category.php";

// get database connection
$database = new Database();
$db = $database->getConnection();

// pass connection to object
$product = new Product($db);
$category = new Category($db);

// set page headers
$page_title = "Create Products";
include_once "layout_header.php";

echo "<div class='right-button-margin'>";
    echo "<a href='index.php' class='btn btn-default pull-right'>Read Products</a>";
echo "</div>";
 
?>

<?php
    // if the form was submitted 
    if(isset($_POST['create'])){
 
        // set product property values
        $product->name = $_POST['name'];
        $product->price = $_POST['price'];
        $product->description = $_POST['description'];
        $product->category_id = $_POST['category_id'];
     
        // image
        $image=!empty($_FILES["image"]["name"])
        ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"]) : "";
        $product->image = $image;

        // if fields not empty and category_id not equal to 0 - zero
        if (!empty($product->name) && !empty($product->price) && !empty($product->description) && !empty($product->category_id != 0)) {

            // create the product
            if($product->create()){
                echo "<div class='alert alert-success'>Product was created.</div>";
                // try to upload the submitted file
                // uploadPhoto() method will return an error message, if any.
                echo $product->uploadPhoto();
            }

              // if unable to create the product, tell the user
        else{
            echo "<div class='alert alert-danger'>Unable to create product.</div>";
        }

        }
        
        // if empty
        else{
            echo "<div class='alert alert-danger'>Input filed required!</div>";
        }

       
     
      
    }
    ?>

<!-- HTML form for creating a product -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data"
 
    <table class='table table-hover table-responsive table-bordered'>
 
        <tr>
            <td>Name</td>
            <td><input type='text' name='name' class='form-control'  /></td>
        </tr>
 
        <tr>
            <td>Price</td>
            <td><input type='text' name='price' class='form-control' /></td>
        </tr>
 
        <tr>
            <td>Description</td>
            <td><textarea name='description' class='form-control'/></textarea></td>
        </tr>
 
        <tr>
            <td>Category</td>
            <td>
            <?php
// read the product categories from the database
$stmt = $category->read();
 
// put them in a select drop-down
echo "<select class='form-control' name='category_id'  />";
    echo "<option>Select category...</option>";
 
    while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row_category);
        echo "<option value='{$id}'>{$name}</option>";
    }
 
echo "</select>";
?>
            </td>
        </tr>

        <tr>
            <td>Photo</td>
            <td><input type="file" name="image" /></td>
        </tr>
 
        <tr>
            <td></td>
            <td>
                <button type="submit" name="create" value="create" class="btn btn-primary">Create</button>
            </td>
        </tr>
 
    </table>
</form>

<?php

// footer
include_once "layout_footer.php";
?>