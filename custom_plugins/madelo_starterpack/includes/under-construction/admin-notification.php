<style>
    .madeloAdminNotification{
        position: fixed;
        bottom:5rem;
        right: 0;
        width: auto;
        height: auto;
        color: red;
        line-height: 5rem;
        font-weight: bold;
        border-bottom: 1px solid rgba(0,0,0,0.5);
        border-top: 1px solid rgba(0,0,0,0.5);
        border-left: 1px solid rgba(0,0,0,0.5);
        text-align: center;
        padding: 0.5rem 1rem;
        background-color: white;
        opacity: 0.5;
        z-index: 9999999;
    }
    .madeloAdminNotification:hover{
        opacity: 1;
    }
    .madeloAdminNotification a{
        color: red;
    }
</style>
<div class="madeloAdminNotification">
    Stránka je v móde under construction | <a href="<?= admin_url("admin.php?page=madelo_under_construction_settings") ?>">Administrácia</a>
</div>