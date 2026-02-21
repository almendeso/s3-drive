<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>S3-Drive</title>
</head>

@include('css.app')

<body>

@include('layouts.app')

<br/>

<div class="toolbar">

<form method="get">
    <input type="hidden" name="path" value="{{ $path }}">
    <input type="text" name="search" placeholder="Buscar..." value="{{ $search }}">
    <button>Buscar</button>
</form>

<form method="post">
    @csrf
    <input type="hidden" name="path" value="{{ $path }}">
    <input type="text" name="newfolder" placeholder="Nova pasta" required>
    <button>Criar</button>
</form>

<form method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="path" value="{{ $path }}">
    <input type="file" name="files[]" multiple required>
    <button>Upload</button>
</form>

</div>

@php
    function sortLink($field) {
        $sort = request('sort', 'name');
        $dir  = request('dir', 'asc');

        $newDir = ($sort === $field && $dir === 'asc') ? 'desc' : 'asc';

        return request()->fullUrlWithQuery([
            'sort' => $field,
            'dir'  => $newDir,
        ]);
    }
@endphp

@if ($path)
    <a href="{{ route('files.index',['path'=>dirname($path)=='.'?'':dirname($path)]) }}">⬅ Voltar</a><br><br>
@endif

<table>
    <thead>
        <tr>
            <th>
                <a href="{{ sortLink('name') }}">
                    Nome
                    @if (request('sort','name') === 'name')
                        {{ request('dir','asc') === 'asc' ? '▲' : '▼' }}
                    @endif
                </a>
            </th>

            <th class="col-size">
                <a href="{{ sortLink('size') }}">
                    Tamanho
                    @if (request('sort') === 'size')
                        {{ request('dir') === 'asc' ? '▲' : '▼' }}
                    @endif
                </a>
            </th>

            <th class="col-date">
                <a href="{{ sortLink('modified') }}">
                    Modificado
                    @if (request('sort') === 'modified')
                        {{ request('dir') === 'asc' ? '▲' : '▼' }}
                    @endif
                </a>
            </th>

            <th class="col-actions"></th>
        </tr>
    </thead>
    
    <tbody>

@foreach ($items as $item)
@php
    $name = $item['name'];
    if (str_starts_with($name, '.')) continue;

    if ($search && stripos($name, $search) === false) continue;

    $date = date('d/m/Y H:i', $item['modified']);
@endphp

<tr>
@if ($item['is_dir'])
    @php $newPath = trim($path.'/'.$name,'/'); @endphp
    <td>📁 <a href="{{ route('files.index',['path'=>$newPath]) }}">{{ $name }}</a></td>
    <td>-</td>
    <td>{{ $date }}</td>
    <td></td>
@else
    @php
        $relative = ltrim(($path ? $path.'/' : '').$name,'/');
        $cdn = $cdnBase.'/'.rawurlencode($relative);
        $cdn = str_replace('%2F','/',$cdn);
    @endphp
    <td>📄 {{ $name }}</td>
    <td>{{ number_format($item['size']/1024,2) }} KB</td>
    <td>{{ $date }}</td>
    <td>
        <a href="{{ $cdn }}" target="_blank">🌐</a>
        <span onclick="copyLink('{{ $cdn }}')">📋</span>
        <form method="post" action="{{ route('files.delete') }}" style="display:inline">
            @csrf
            @method('DELETE')
            <input type="hidden" name="path" value="{{ $path }}">
            <input type="hidden" name="file" value="{{ $name }}">
            <button onclick="return confirm('Excluir arquivo?')">🗑️</button>
        </form>
    </td>
@endif
</tr>
@endforeach

</tbody>
</table>

<script>
function copyLink(link) {
    navigator.clipboard.writeText(link)
        .then(() => alert('Link copiado:\n'+link));
}
</script>

</body>
</html>