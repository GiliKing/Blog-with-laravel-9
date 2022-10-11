<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        @vite('resources/css/app.css')
    </head>
    <body>
    <div class="w-4/5 mx-auto">
        <div class="text-center pt-20">
            <h1 class="text-3xl text-gray-700">
                Edit {{ $post->title }}
            </h1>
            <hr class="border border-1 border-gray-300 mt-10">
        </div>
    
    <div class="m-auto pt-20">
        <div class="pb-8">
            @if($errors->any())
                <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                    Some went wrong...
                </div>
                <ul class="border border-t-0 border-red-400 rounded-b bg-red-200 px-4 py-3 text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <form
            action="{{ route('blog.update', $post->id) }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')
    
            <label for="is_published" class="text-gray-500 text-2xl">
                Is Published
            </label>
            <input
                type="checkbox"
                {{ $post->is_published === true ? 'checked' : '' }}
                class="bg-transparent block border-b-2 inline text-2xl outline-none"
                name="is_published
                ">
    
            <input
                type="text"
                name="title"
                placeholder="Title..."
                class="bg-transparent block border-b-2 w-full h-20 text-2xl outline-none"
                value={{ $post->title }}
            >
    
            <input
                type="text"
                name="excerpt"
                placeholder="Excerpt..."
                class="bg-transparent block border-b-2 w-full h-20 text-2xl outline-none"
                value={{ $post->excerpt }}>
    
            <input
                type="number"
                name="min_to_read"
                placeholder="Minutes to read..."
                class="bg-transparent block border-b-2 w-full h-20 text-2xl outline-none
                "
                value={{ $post->min_to_read }}>
    
            <textarea
                name="body"
                placeholder="Body..."
                class="py-20 bg-transparent block border-b-2 w-full h-60 text-xl outline-none
            ">
            {{ $post->body }}
            </textarea>

            <div class="bg-grey-lighter py-10">
                <label class="w-44 flex flex-col items-center px-2 py-3 bg-white-rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer">
                        <span class="mt-2 text-base leading-normal">
                            Select a file
                        </span>
                    <input
                        type="file"
                        name="image"
                        class="hidden"
                        >

                </label>

            </div>
            <input type="hidden" name="image_path" value="{{ $post->image_path }}">

            <input
            type="text"
            name="meta_description"
            placeholder="Meta Description..."
            class="bg-transparent block border-b-2 w-full h-20 text-2xl outline-none
            "

            {{-- {{ dd($postmeta->meta_description) }} --}}
            
            @if ($postmeta !== null)
                value="{{ $postmeta->meta_description }}"
            @else
                {{ '' }}
            @endif
        >

        <input
            type="text"
            name="meta_keywords"
            placeholder="Meta Keywords..."
            class="bg-transparent block border-b-2 w-full h-20 text-2xl outline-none
            "
            @if ($postmeta !== null)
                value="{{ $postmeta->meta_keywords }}"
            @else
                {{ '' }}
            @endif
        >

        <input
            type="text"
            name="meta_robots"
            placeholder="Meta Robots..."
            class="bg-transparent block border-b-2 w-full h-20 text-2xl outline-none
            "
            @if ($postmeta !== null)
                value="{{ $postmeta->meta_robots }}"
            @else
                {{ '' }}
            @endif
        >
            <button
                type="submit"
                class="uppercase mt-15 bg-blue-500 text-gray-100 text-lg font-extrabold py-4 px-8 rounded-3xl">
                Submit Post
            </button>
        </form>
    </div>
    </body>
    </html>