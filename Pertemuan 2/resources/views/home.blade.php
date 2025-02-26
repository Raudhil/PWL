<html>
    <body>
        <div class="container text-center mt-5">
            <h1>Selamat Datang di Website Kami</h1>
            <p>Ini adalah halaman utama dari website.</p>
            
            <ul>
                <li>
                <a href="{{ url('/foodbeverage') }}">Food & Beverage</a>
                </li>
                <li>
                    <a href="{{ url('/beautyhealth') }}">Beauty & Health</a> 
                </li>
                <li>
                    <a href="{{ url('/homecare') }}">Home Care</a> 
                </li>
                <li>
                    <a href="{{ url('/babykid') }}">Baby & Kid</a>
                </li>
            </ul>

            <a href="{{ url('/transaksi') }}">Halaman Transaksi</a>

            
            
            
        </div>
    </body>
</html>