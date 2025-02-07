<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title>MYKINGDOM - CONTACT</title>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CONTACT US</h2>
        <div class="bg-dark h-line"></div>
        <p class="text-center mt-3">Lorem ipsum, or lipsum as it is sometimes known, is <br> dummy text used in laying out print, graphic or web designs.
            The passage is attributed to an unknown typesetter in the 15th century.
        </p>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.6697269761835!2d106.67968337488225!3d10.759917089387907!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f1b7c3ed289%3A0xa06651894598e488!2sSaigon%20University!5e0!3m2!1sen!2s!4v1713673191021!5m2!1sen!2s"  height="320" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <h5>Address</h5>
                    <a href="https://maps.app.goo.gl/5ZzQ3UUUeW9rPk1Z8" target="_blank" class="d-inline-block text-decoration-none text-dark">
                        <i class="bi bi-geo-alt-fill">SGU</i>
                    </a>

                    <h5 class="mt-4">Call us</h5>
                    <a href="tel: +88888888 ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +88888888
                    </a>
                    <br>
                    <a href="tel: +7777777" class="d-inline-block text-decoration-none text-dark">
                                <i class="bi bi-telephone-fill"></i> +7777777
                    </a>

                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: huynhngoctuan48@gmail.com" class="d-inline-block text-decoration-none text-dark">
                        <i class="bi bi-envelope-fill"></i> huynhngoctuan48@gmail.com
                    </a>

                    <h5 class="mt-4">Follow us</h5>
                    <a href="https://x.com" class="d-inline-block text-dark fs-5 me-2">
                                <i class="bi bi-twitter-x"></i>
                    </a> 
                    <a href="https://www.facebook.com/" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/" class="d-inline-block text-dark fs-5">
                        <i class="bi bi-instagram"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form action="" method="POST">
                        <h5>Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight:500;">Name</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight:500;">Email</label>
                            <input name="email" type="email" required class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight:500;">Phone</label>
                            <input name="phone" type="tel" maxlength="10" required class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight:500;">Subject</label>
                            <input name="subject" type="text" required class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight:500;">Message</label>
                            <textarea name="message" required class="form-control shadow-none" rows="5" style="resize:none;"></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custom-bg mt-3">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        if(isset($_POST['send']))
        {
            $frm_data=filteration($_POST);
            $createAt=date('Y-m-d');
            $q="INSERT INTO `contact`(`name`, `email`,`phone`, `subject`, `message`,`create_at`) VALUES (?,?,?,?,?,?)";
            $values=[$frm_data['name'],$frm_data['email'],$frm_data['phone'],$frm_data['subject'],$frm_data['message'],$createAt];
            $res=insert($q,$values,'ssssss');
            if($res==1){
                alert('success','Mail sent');
            } else {
                alert('error','Server Down! Try again later.');
            }
        }

    ?>
    <?php require('inc/footer.php'); ?>
</body>

</html>