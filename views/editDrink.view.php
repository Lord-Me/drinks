<!DOCTYPE html>
<html lang="en">
<?php
require('partials/head.partials.php');
?>
<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
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
        let text = document.getElementsByClassName('text');
        //textArr.forEach(myFunction);
        for (let i=0; i<text.length; i++){
            function resize() {
                text[i].style.height = 'auto';
                text[i].style.height = text[i].scrollHeight + 'px';
            }

            /* 0-timeout to get the already changed text[i] */
            function delayedResize() {
                window.setTimeout(resize, 0);
            }

            observe(text[i], 'change', resize);
            observe(text[i], 'cut', delayedResize);
            observe(text[i], 'paste', delayedResize);
            observe(text[i], 'drop', delayedResize);
            observe(text[i], 'keydown', delayedResize);

            text[i].focus();
            text[i].select();
            resize();

        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
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
<br><br><br><br>
<section>
    <div style='height: 59px'></div>
    <form action="/drinks/user/myDrinks/editDrink/<?=$drink->getId()?>" method="post"  enctype="multipart/form-data" class="addForm">
        <div class="container">
            <div class="row align-items-center">
                <div class='col-lg-6 order-lg-1'>
                    <div class="addFormTitle p-5">
                        <h1 class="display-4"><textarea class="text" style="height:1em" type="text" name="title"><?=$drink->getTitle()?></textarea></h1>
                    </div>
                </div>
                <div class='col-lg-6 order-lg-2'>
                    <div class="addFormImage p-5">
                        <label for="fileToUpload"><img id="preview" class="img-fluid rounded-circle" src="/drinks/img/<?=$drink->getImage()?>" alt="<?=$drink->getImage()?>"></label><br>
                        <input onchange="readURL(this);" type="file" name="fileToUpload" placeholder="Preview Image" id="fileToUpload">
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class='col-lg-6'>
                    <table>
                        <tr>
                            <td>Author: <?=ucfirst($user->getUsername())?> <img src='/drinks/img/avatars/<?=$user->getAvatar()?>' alt='userImage' width='30' height='30' class='rounded-circle'></td>
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
                        <p class="errorText"><?=implode("<br>", $errorText)?></p>
                        <p class="successText"><?=implode("<br>", $successText)?></p>
                        <input class="my-btn-left btn btn-primary my-btn-xl" type="submit" value="Submit">
                        <a href="#" class="my-btn-right btn btn-primary my-btn-xl" onclick="history.go(-1);">Back </a>
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
