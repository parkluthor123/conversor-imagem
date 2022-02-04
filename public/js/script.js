const form = document.querySelector("form");
const image = document.querySelector('#image');
const labelImage = document.querySelector("label[for='image']")
const currentFormat = document.querySelector('#fromImage');
const newFormat = document.querySelector("#toImage");

image.addEventListener('change', function(){
    if(this.files.length > 0)
    {
        labelImage.classList.add('image-selected');
    }
})

form.addEventListener("submit", async (e)=>{
    e.preventDefault();
    const data = {
        image: image.files[0],
        currentFormat: currentFormat.value,
        newFormat: newFormat.value
    }

    const formData = new FormData();
    formData.append('current', data.currentFormat);
    formData.append('newFormat', data.newFormat);
    formData.append('image', data.image);

    await fetch('http://localhost/image-converter/Controllers/ConverterController.php', {
        method: 'post',
        headers:{
            'Accept': 'application/json',
            'content-type': 'application/json'
        },
        body: formData,
    })
    .then((response)=>response.text())
    .then((responseJson)=>{
        console.log(responseJson)
    })
})