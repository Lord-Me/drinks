<!DOCTYPE html>
<html lang="en">
<?php
require('partials/head.partials.php');
?>
<script>
    var observe;
    if (window.attachEvent) {
        observe = function (element, event, handler) {
            element.attachEvent('on'+event, handler);
        };
    }
    else {
        observe = function (element, event, handler) {
            element.addEventListener(event, handler, false);
        };
    }
    function init () {
        var text = document.getElementsByClassName('text');
        text.forEach(myFunction);//TODO this foreach not working
        function myFunction(text){
            function resize() {
                text.style.height = 'auto';
                text.style.height = text.scrollHeight + 'px';
            }

            /* 0-timeout to get the already changed text */
            function delayedResize() {
                window.setTimeout(resize, 0);
            }

            observe(text, 'change', resize);
            observe(text, 'cut', delayedResize);
            observe(text, 'paste', delayedResize);
            observe(text, 'drop', delayedResize);
            observe(text, 'keydown', delayedResize);

            text.focus();
            text.select();
            resize();
        }
    }
</script>
</head>
<body onload="init();">
<?php
if (isset($_SESSION['loggedin'])) {
    require('partials/navigationLoggedIn.partials.php');
}else {
    require('partials/navigation.partials.php');
}
?>
<br><br><br>
<section>
    <div style='height: 59px'></div>
    <form action="index.php?page=editDrink&id=<?=$drink->getId()?>" method="post"  enctype="multipart/form-data" class="addForm">
        <div class="container">
            <div class="row align-items-center">
                <div class='col-lg-6 order-lg-1'>
                    <div class="addFormTitle">
                        <h1 class="display-4"><textarea class="text" style="height:1em" type="text" name="title"><?=$drink->getTitle()?></textarea></h1>
                    </div>
                </div>
                <div class='col-lg-6 order-lg-2'>
                    <div class="addFormImage">
                        <label for="fileToUpload"><img class="img-fluid rounded-circle" src="img/<?=$drink->getImage()?>" alt="<?=$drink->getImage()?>"></label><br>
                        <input type="file" name="fileToUpload" placeholder="Preview Image" id="fileToUpload">
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class='col-lg-6'>
                    <table>
                        <tr>
                            <td>Author: <?=ucfirst($user->getUsername())?> <img src='img/avatars/<?=$user->getAvatar()?>' alt='userImage' width='30' height='30' class='rounded-circle'></td>
                        </tr>
                        <tr>
                            <td>Posted at: <?=$drink->getPublished_at()?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row align-items-center">
                <div class='col-lg-6'>
                    <div class="addFormIngredients">
                        <h3>Ingredients</h3>
                        <textarea class="text" style="height:1em" name="ingredients"><?=$drink->getIngredients()?></textarea>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="addFormContent">
                        <h3>Instructions</h3>
                        <textarea class="text" style="height:1em" name="content"><?=$drink->getContent()?></textarea>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="addFormCategory">
                        <?php
                        $requiredPro = $requiredOri = "";
                        if($drink->getCategory() == 1){$requiredPro="checked";}
                        if($drink->getCategory() == 2){$requiredOri="checked";}
                        ?>
                        <input id="pro" type="radio" name="category" value=1 class="category" checked="<?=$requiredOri.$requiredPro?>">
                        <label for="pro">Professional Drinks</label>

                        <input id="ori" type="radio" name="category" value=2 class="category" checked="<?=$requiredOri.$requiredPro?>">
                        <label for="ori">Original Drinks</label>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="row filterFormSubmit">
                        <input type="submit" value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>


<?php
require('partials/footer.partials.php');
?>
</body>
</html>
