<style>
    @media screen and (max-width: 915px) {
        .menuitem {
            display: none !important;
        }
    }
    @media screen and (max-width: 767px) {
        .menuitem {
            display: inline !important;
        }
    }
    body {
        background-image: url('/assets/images/bg2.jpg');
        background-attachment: fixed;
    }
    label, .control-label {
        color: rgba(91, 91, 91, 1) !important;

    }
    * {
        font-smooth: always !important;
        font-smooth: 2em !important;
        -webkit-font-smoothing: subpixel-antialiased !important;
        -moz-osx-font-smoothing: grayscale !important;
        font-family: Tahoma, sans-serif;;
        text-shadow: rgba(0, 0, 0, 0.18) 1px 1px 1px;
        -webkit-text-shadow: rgba(0, 0, 0, 0.18) 1px 1px 1px;
    }
    img.emoji {
    // Override any img styles to ensure Emojis are displayed inline
    margin: 0px !important;
        display: inline !important;
    }
    #preloader {
        display: none;
        margin: auto;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        z-index: 9999999999;
    }
</style>