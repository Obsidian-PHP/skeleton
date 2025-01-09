<div id="flash-alert" class="col-md-7 animate__animated animate__fadeIn">
    <?php flashRender(); ?>
</div>

<style>
#flash-alert {
  position: absolute;
  bottom: 10px;
  right: 10px;
  max-width: 350px;
}
</style>

<script>function masquernotification() { document.getElementById("flash-alert").innerHTML="";} window.setTimeout(masquernotification, 2000);</script>