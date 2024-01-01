@include('header',['page_title'=>'Links'])
        <header>
            <img src="photo.jpg" id="main_image">
            <h1>{{ env('YOUR_LARGE_TEXT_UNDER_THE_PHOTO') }}</h1>
            <h2>{{ env('YOUR_SMALLER_TEXT_UNDER_THE_PHOTO') }}</h2>
        </header>
        <main>
        @foreach ($link_types as $link_type)
            <h3>{{ $link_type->name }}</h3>
            <ul>
            @foreach ($links as $link)
                @if ($link->link_type == $link_type->id)
                    <li><a href="go/{{ $link->id }}">{{ $link->name }}</a></li>
                @endif
            @endforeach
            </ul>
        @endforeach
        </main>
        <footer>
            <a href="legal">{{ env('YOUR_LEGAL_LINK_A') }}</a>
        </footer>
@include('footer')