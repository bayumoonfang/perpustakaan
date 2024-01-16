<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak</title>
</head>

<body>
    <table border="1" style="width: 100%">
        {{-- {{ $isi }} --}}
        {{-- @for ($i = 0; $arr_url['key'] < 5; $i++)
            <tr>
                @foreach ($arr_url as $item)
                    <td style="text-align:center;">{{ $item['judul'] }} <br>{{ $item['nomor'] }}
                        <br>{{ $item['penerbit'] }}<br>{{ $item['inisial'] }}<br>{{ $item['keterangan'] }}
                    </td>
                @endforeach
            </tr>

        @endfor --}}
        @php
            $i = 0;
        @endphp
        @foreach ($arr_url as $key => $item)
            @if ($i > 4)
                @php $i = 0;@endphp
            @endif
            @if ($key == 5 || $i == 0)
                <tr>
            @endif
            <td style="text-align:center;width:150px;">
                <strong>{{ $item['judul'] }}</strong> <br>
                {{ $item['nomor'] }} <br>
                {{-- {{ $key . ' ' . $i }} <br> --}}
                {{ $item['penerbit'] }}<br>
                {{ $item['inisial'] }}<br>
                {{ $item['keterangan'] }}
            </td>
            @if ($i == 4 || $key == 4)
                </tr>
            @endif
            @php
                $i++;
            @endphp
        @endforeach

    </table>

    <script>
        window.print();
    </script>
</body>

</html>
