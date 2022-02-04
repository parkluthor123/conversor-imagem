<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de imagens</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::to('css/style.css') }}">
</head>
<body>
    <div class="container">
        <form enctype="multipart/form-data">
            @csrf
            <div class="form-item">
                <label for="image">Envie a imagem</label>
                <input type="file" name="image" id="image">
            </div>
            <br/>
            <div class="form-range">
                <span>Qualidade:</span>
                <input type="range" max="10" id="quality" value="5">
            </div>
            <br/>
            <div class="form-item">
                <span>Para:</span>
                <select name="toImage" id="toImage">
                    <option hidden disabled selected>Selecione um formato</option>
                    <option value="jpg">JPG</option>
                    <option value="png">PNG</option>
                    <option value="webp">WEBP</option>
                </select>
            </div>
            <div class="btn-area">
                <button type="submit">Converter</button>
            </div>
        </form>
        <div class="btn-download">
            <a href="#">Baixar</a>
        </div>
    </div>
    <div class="loading-wrapper">
        <span class="loading"></span>
    </div>
    <script src="{{ URL::to('js/axios.min.js') }}"></script>
    <script>
        const form = document.querySelector("form");
        const range = document.querySelector("#quality")
        const image = document.querySelector('#image');
        const labelImage = document.querySelector("label[for='image']")
        const newFormat = document.querySelector("#toImage");
        const btnDownloadWrapper = document.querySelector(".btn-download");
        const btnDownload = document.querySelector(".btn-download a");
        const loading = document.querySelector(".loading-wrapper");

        function displayLoading(boolean)
        {
            if(boolean)
            {
                loading.classList.add('show-loading');
            }
            else
            {
                loading.classList.remove('show-loading');
            }
        }

        image.addEventListener('change', function(){
            if(this.files.length > 0)
            {
                labelImage.classList.add('image-selected');
            }
        })

        form.addEventListener("submit", async (e)=>{
            e.preventDefault();
            displayLoading(true)
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let imageArray = new Array();
            imageArray = image.files[0];
            console.log(range.value)

            const formData = new FormData();
            formData.append('newFormat',  newFormat.value);
            formData.append('image', imageArray);
            formData.append('quality', range.value);
                
            if(image.files.length > 0)
            {
                await axios({
                    method: 'post',
                    url: "{{ route('converter') }}",
                    headers:{
                        'Accept': 'application/json',
                        'content-type': 'multipart/form-data; boundary=something',
                        'X-CSRF-TOKEN': csrf
                    },
                    data: formData
                })
                .then((response)=>{
                    displayLoading(false)
                    if(response.status == 200)
                    {
                        btnDownloadWrapper.style.cssText = `display: flex`;
                        btnDownload.href = `${response.data}`;
                        btnDownload.download = `new-image`;
                        labelImage.classList.remove('image-selected');
                        form.reset();
                    }
                })
            }
            else
            {
                alert("Insira uma imagem válida");
            }
        })
    </script>
</body>
</html>