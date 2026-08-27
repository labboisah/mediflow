<div id="page-loader"
     class="page-loader">

<div class="loader-box">

    <div class="spinner-border text-success"
         role="status">

        <span class="visually-hidden">

            Loading...

        </span>

    </div>

    <div class="mt-3">

        Loading page...

    </div>

</div>

</div>

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const loader =
            document.getElementById(
                'page-loader'
            );

        if(loader){

            loader.classList.add(
                'hidden'
            );

        }

    }
);

</script>
