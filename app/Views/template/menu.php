<aside class="menu">
   <div class="logo-menu">
      <img src="<?= base_url('uploads/local/escudo.png?t') . time() ?>">
      <h5 class="fw-bold mt-3"><?= @$institucion_nombre ?></h5>
   </div>
   <ul class="menu-list pt-2">
      <li class="<?= is_null(esc($layout_menu_name)) ? 'active' : '' ?>">
         <a href="<?= MODULO_URL ?>">
            <i class="far fa-home"></i>
            <span class="links_name">
               Principal
            </span>
         </a>
      </li>
      <?php echo App\Helpers\Funciones::crearArbolMenuHtml(@$layout_menuArbol, @$layout_menu_name) ?>
   </ul>
</aside>
<script>
   $('.menu li:has(ul)').click(function(e) {
      e.preventDefault();
      if ($(this).hasClass('active')) {
         $(this).removeClass('active');
         $(this).children('ul').slideUp();
      } else {
         $('.menu li ul').slideUp();
         $('.menu li').removeClass('active');
         $(this).addClass('active');
         $(this).children('ul').slideDown();
      }
   });
   $('.menu li ul li a').click(function() {
      window.location.href = $(this).attr('href');
   });
</script>