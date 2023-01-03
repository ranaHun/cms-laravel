<head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<x-layout>
        <form id="create-article" method="POST" action="" enctype="multipart/form-data">
                @csrf

                <x-form.input name="title" required />
                <x-form.input name="slug" required />
                <x-form.editor></x-form.editor>
                <x-form.button form_id="create-article">Publish</x-form.button>
        </form>
</x-layout>
<script>
        const editor = new window.EditorJS({
                /**
                 * Id of Element that should contain Editor instance
                 */
                holder: 'editorjs',
                tools: {
                        image: window.SimpleImage,
                        GifTool: GifTool
                }
        });

        document.querySelector('#create-article').addEventListener('submit', (e) => {
                e.preventDefault();
                var formData = new FormData(e.target);
                console.log(formData)
                var url = '/admin/articles';
                editor.save().then((outputData) => {
                        saveData(formData, outputData)
                }).catch((error) => {
                        console.log('Saving failed: ', error)
                });

        });

        function saveData(formData, data) {
                var url = '/admin/articles';
                $.ajaxSetup({
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                });
                $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                                title: formData.get('title'),
                                slug: formData.get('slug'),
                                body: JSON.stringify(data),
                        },
                }).done(function(response) {
                        console.log('received this response: ' + response);
                });
        }
</script>