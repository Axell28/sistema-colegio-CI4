<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="shortcut icon" href="<?= base_url('img/iconos/web_ico.png') ?>" type="image/png">
   <link rel="preload" href="<?= base_url('css/pace.css') ?>" as="style" fetchpriority="high">
   <link rel="preload" href="<?= base_url('js/pace.min.js') ?>" as="script" etchpriority="high">
   <title>Login - Sistema Académico</title>
   <link rel="preload" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" as="style" fetchpriority="high">
   <link rel="stylesheet" href="<?= base_url('css/pace.css') ?>">
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
   <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
</head>

<body>

   <script src="<?= base_url('js/pace.min.js') ?>"></script>
   <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>

   <style>
      :root {
         --font-size: 13px;
         --color-main: var(--bs-primary);
         --color-body: #4b4b4b;
         --color-mute: #bebebe;
      }

      body {
         font-size: var(--font-size);
         height: 100vh;
         color: var(--color-body);
         overflow: hidden;
      }

      .contenedor {
         position: fixed;
         z-index: 9;
         top: 0;
         left: 0;
         width: 100%;
         height: 100vh;
         background: linear-gradient(180deg, rgba(52, 177, 166, 0.5), rgba(6, 18, 71, .8));
      }

      .box-login {
         position: relative;
         top: 50%;
         left: 50%;
         width: 100%;
         max-width: 350px;
         background: rgb(255, 255, 255);
         padding: 2.5em 2.3em;
         transform: translate(-50%, -50%);
         border-radius: .5em;
         z-index: 99;
         box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px;
      }

      .box-login h3 {
         font-size: 16px;
         font-weight: bold;
         letter-spacing: 1px;
      }

      .input-field {
         position: relative;
         height: 48px;
         width: 100%;
         margin-top: 28px;
      }

      .input-field input {
         color: var(--color-body);
         position: absolute;
         height: 100%;
         width: 100%;
         padding: 0 44px;
         outline: none;
         font-size: var(--font-size);
         border-radius: .3em;
         border: 1px solid #BABBBC;
         -webkit-appearance: none;
         -moz-appearance: none;
         appearance: none;
      }

      .input-field input:is(:focus) {
         border-color: var(--bs-primary);
      }

      .input-field i {
         position: absolute;
         top: 50%;
         left: 1em;
         transform: translateY(-50%);
         color: #929292;
         font-size: 15px;
      }

      .input-field input:is(:focus)~i {
         color: var(--bs-primary);
      }

      .input-field i.icon {
         left: 0;
      }

      .btn {
         font-size: var(--font-size);
      }

      .carousel-item img {
         min-height: 100vh;
      }

      @media only screen and (max-width: 700px) {
         .carousel-item img {
            object-fit: cover;
            min-height: 100vh;
         }
      }
   </style>

   <div class="carousel slide carousel-fade" data-bs-ride="carousel">
      <div class="carousel-inner">
         <div class="carousel-item active" data-bs-interval="5000">
            <img src="<?= base_url('/img/default/background_01.jpg') ?>" class="d-block w-100">
         </div>
         <div class="carousel-item" data-bs-interval="5000">
            <img src="<?= base_url('/img/default/background_02.jpg') ?>" class="d-block w-100">
         </div>
         <div class="carousel-item" data-bs-interval="5000">
            <img src="<?= base_url('/img/default/background_03.jpg') ?>" class="d-block w-100">
         </div>
      </div>
   </div>

   <div class="contenedor">
      <div class="box-login">
         <h3 class="mb-3">LOGIN</h3>
         <div style="width: 27px; border-top: 3px solid var(--bs-primary);"></div>
         <?php if (!empty(session('errorMsg'))) { ?>
            <div class="alert alert-danger mt-4 mb-0" role="alert">
               <i class="fad fa-exclamation-triangle"></i>
               <span>&nbsp;<?= session('errorMsg') ?></span>
            </div>
         <?php } ?>
         <form action="<?= site_url('auth/authenticate') ?>" method="post" autocomplete="off">
            <?= csrf_field() ?>
            <div class="input-field">
               <input type="text" name="usuario" value="<?= old('usuario') ?>" placeholder="Usuario">
               <i class="fad fa-user"></i>
            </div>
            <?php if (!empty(session('error.usuario'))) { ?>
               <div class="text-danger mt-1">
                  <i class="fad fa-exclamation-circle"></i>
                  <span>&nbsp;<?= session('error.usuario') ?></span>
               </div>
            <?php } ?>
            <div class="input-field">
               <input class="password" type="password" name="password" value="<?= old('password') ?>" placeholder="Contraseña">
               <i class="fad fa-lock-alt"></i>
               <i class="uil uil-eye-slash showHidePw"></i>
            </div>
            <?php if (!empty(session('error.password'))) { ?>
               <div class="text-danger mt-1">
                  <i class="fad fa-exclamation-circle"></i>
                  <span>&nbsp;<?= session('error.password') ?></span>
               </div>
            <?php } ?>
            <div class="form-check my-4 ms-1">
               <input class="form-check-input" type="checkbox" value="S" name="remember" id="chkRemember" style="transform: scale(1.1)">
               <label class="form-check-label" for="chkRemember">&nbsp;Recordar sesión</label>
            </div>
            <div class="mt-3 mb-1">
               <button class="btn btn-primary w-100 py-2" type="submit">
                  <span>Ingresar&nbsp;</span>
                  <i class="far fa-arrow-right"></i>
               </button>
            </div>
         </form>
      </div>
   </div>

   <div class="fixed-bottom text-center pb-2">
      <span style="color: var(--color-mute)">Sistema Académico &copy; <?= date('Y') ?> </span>
   </div>

</body>

</html>