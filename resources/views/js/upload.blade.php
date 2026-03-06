<script>

document.getElementById("uploadForm").addEventListener("submit", function(e){

    e.preventDefault();

    const form = this;
    const data = new FormData(form);

    const xhr = new XMLHttpRequest();

    const progress = document.getElementById("uploadProgress");
    const bar = document.getElementById("progressBar");

    progress.style.display = "block";

    xhr.upload.addEventListener("progress", function(e){

        if(e.lengthComputable){

            const percent = (e.loaded / e.total) * 100;
            bar.style.width = percent + "%";

        }

    });

    xhr.onload = function(){

        if(xhr.status === 200){

            bar.style.width = "100%";

            showToast("Upload concluído");

            setTimeout(()=>location.reload(),500);

        }else{

            showToast("Erro no upload");

        }

    };

    xhr.open("POST", form.action);
    xhr.send(data);

});

</script>