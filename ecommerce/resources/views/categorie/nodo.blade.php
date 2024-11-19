<li>
    {{ $nodo->nome }}
    @if ($nodo->figli->count())
        <ul>
            @foreach ($nodo->figli as $figlio)
                @include('categorie.nodo', ['nodo' => $figlio])
            @endforeach
        </ul>
    @endif
</li>
