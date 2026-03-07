<script>
function confirmDeleteFolder(path){

    fetch("{{ route('files.folderInfo') }}",{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body:JSON.stringify({path:path})
    })
    .then(r=>r.json())
    .then(data=>{

        const msg =
            "Excluir pasta?\n\n"+
            "Arquivos: "+data.files+"\n"+
            "Subpastas: "+data.dirs+"\n\n"+
            "Todo conteúdo será removido.";

        if(confirm(msg)){

            const form = document.createElement("form");

            form.method="POST";
            form.action="{{ route('files.deleteFolder') }}";

            form.innerHTML=`
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="path" value="${path}">
            `;

            document.body.appendChild(form);
            form.submit();
        }

    });

}
</script>