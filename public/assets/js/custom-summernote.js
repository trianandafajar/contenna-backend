$(document).ready(function () {
    $('#summernote').summernote({
        placeholder: 'Enter your blog content...',
        tabsize: 2,
        height: 500,
        codemirror: { // Aktifkan tampilan dengan CodeMirror
            theme: 'monokai'
        },
        toolbar: [
            ['style', ['style', 'undo', 'redo']],
            ['font', ['bold', 'underline','strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontsize', ['fontsize', 'fontname', 'fontsizeunit']],
            ['color', ['forecolor', 'backcolor']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video', 'hr']],
            ['view', ['help']],
            ['custom', ['codeBlockButton']]
        ],
        buttons: {
            codeBlockButton: function (context) {
                var ui = $.summernote.ui;

                // Daftar bahasa
                var languages = [
                    'javascript', 
                    'java', 
                    'html', 
                    'css', 
                    'ts',      
                    'tsx',     
                    'jsx',     
                    'python', 
                    'bash',   
                    'kotlin',
                    'dart'
                ];
                
                // Tombol utama
                var button = ui.button({
                    contents: `
        <div style="
            display: flex;
            align-items: center;
            justify-content: center;
        ">
            <span class="material-symbols-outlined">code</span>
            <span style="margin-left: 5px;">Add Code</span>
        </div>
    `,
                    tooltip: 'Insert Code Block',
                    click: function (event) {
                        // Check if a dropdown already exists
                        if ($('.dropdown-container').length > 0) {
                            return; // Don't create a new dropdown if one is already active
                        }
                
                        // Create a container for the dropdowns
                        var dropdownContainer = $('<div class="absolute bg-white border border-gray-300 rounded shadow-md py-2 z-50 flex dropdown-container"></div>');
                
                        // Divide languages into chunks of 5
                        var chunkSize = 5;
                        for (var i = 0; i < languages.length; i += chunkSize) {
                            var chunk = languages.slice(i, i + chunkSize);
                
                            // Create a dropdown for each chunk
                            var dropdown = $('<div class="w-[5rem]"></div>');
                            chunk.forEach(function (lang) {
                                var item = $(`<button
                                                type="button"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                            ></button>`)
                                    .text(lang)
                                    .click(function () {
                                        // Add the <pre> element with class `language-[language]`
                                        var preHTML = `
                                            <pre id="language-${lang}"
                                                class="bg-black text-white p-4 rounded overflow-x-auto line-numbers"
                                                style="font-family: 'Fira Code', monospace;"
                                            ></pre>`;
                                        context.invoke('editor.pasteHTML', preHTML);
                                        $('.dropdown-container').remove(); // Remove all dropdowns after language selection
                                    });
                                dropdown.append(item);
                            });
                
                            // Append the dropdown to the container
                            dropdownContainer.append(dropdown);
                        }
                
                        // Append the dropdown container to the body
                        $('body').append(dropdownContainer);
                
                        // Position the dropdown near the event
                        dropdownContainer.css({
                            top: event.pageY + 'px',
                            left: event.pageX + 'px',
                        });
                
                        // Close the dropdown when the user clicks outside
                        $(document).one('click', function () {
                            dropdownContainer.remove();
                        });
                
                        return false;
                    }
                });
                
                

                return button.render();
            }
        },
        callbacks: {
            onInit: function () {
                $('.note-editable').addClass('prose max-w-[100%]');
                $('.note-dropdown-item').addClass('prose');
            },
            onChange: function (contents) {
                $('#content').val(contents);
                if (contents.trim() !== '') {
                    $('#summernote').summernote('placeholder', ''); // Hilangkan placeholder jika ada konten
                }
            
                // Hapus gambar dengan `data-filename="image.png"` setelah memperbarui konten
                const imagesWithFilename = $('.note-editable').find('img[data-filename="image.png"]');
                imagesWithFilename.each(function () {
                    $(this).remove();
                });
            },
            onKeyup: function (e) {
                const selection = window.getSelection();
                const editableDiv = $('.note-editable').get(0);
                let content = editableDiv.innerHTML;

                const regex = /```([^`]*)```/g;
                if (regex.test(content)) {
                    content = content.replace(regex, function (match, code) {
                        return `<pre class="bg-[#000000] text-white p-4 [overflow-x:auto]" style="border-radius:0px;"> ${code} </pre>`;
                    });
                    editableDiv.innerHTML = content;

                    const preElement = editableDiv.querySelector('pre');
                    const range = document.createRange();
                    range.setStart(preElement, 1);
                    range.setEnd(preElement, 1);
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            },
            onKeydown: function (e) {
                const selection = window.getSelection();
                if (!selection.rangeCount) return;
                const range = selection.getRangeAt(0);
                const currentNode = selection.anchorNode.parentElement;
            
                // Membuat blok kode <pre> saat menekan Shift + `
                if (e.key === '`' && e.shiftKey && !e.ctrlKey && !e.altKey) {
                    e.preventDefault();
            
                    const preElement = document.createElement('pre');
                    preElement.className = "bg-[#000000] text-white p-4 overflow-x-auto";
                    preElement.style.borderRadius = "4px";
                    preElement.innerHTML = "<code>\n\n</code>";
            
                    range.deleteContents();
                    range.insertNode(preElement);
            
                    // Pindahkan kursor ke dalam <code> di dalam <pre>
                    const codeElement = preElement.querySelector('code');
                    const newRange = document.createRange();
                    newRange.setStart(codeElement, 0);
                    newRange.collapse(true);
                    selection.removeAllRanges();
                    selection.addRange(newRange);
                } 
                // Jika menekan Enter di dalam <pre>, keluar dari blok kode
                else if (e.key === 'Enter' && !e.shiftKey) {
                    if (currentNode.tagName === 'PRE' || currentNode.closest('pre')) {
                        e.preventDefault();
            
                        // Buat paragraf baru di luar <pre>
                        const newParagraph = document.createElement('p');
                        newParagraph.innerHTML = '<br>';
                        
                        // Sisipkan setelah <pre>
                        currentNode.closest('pre').insertAdjacentElement('afterend', newParagraph);
            
                        // Pindahkan kursor ke dalam <p> baru
                        const newRange = document.createRange();
                        newRange.setStart(newParagraph, 0);
                        newRange.collapse(true);
                        selection.removeAllRanges();
                        selection.addRange(newRange);
                    }
                } 
                // Menangani kasus tambahan jika mengetik ` dalam kode blok
                else if (e.key === '`') {
                    const previousContent = currentNode ? currentNode.innerHTML : '';
            
                    if (previousContent.endsWith('``') && !previousContent.endsWith('```')) {
                        e.preventDefault();
                        const newContent = previousContent + '````';
            
                        currentNode.innerHTML = newContent;
            
                        const newRange = document.createRange();
                        newRange.setStart(currentNode.childNodes[0], newContent.length - 3);
                        newRange.setEnd(currentNode.childNodes[0], newContent.length - 3);
                        selection.removeAllRanges();
                        selection.addRange(newRange);
                    }
                }
            },
            
            onPaste: function (e) {
                e.preventDefault(); // Prevent default paste behavior
        
                const clipboardData = e.originalEvent.clipboardData || window.clipboardData;
                const pastedHtml = clipboardData.getData('text/html') || clipboardData.getData('text/plain');
        
                // Temp container untuk parsing HTML
                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = pastedHtml;
        
                const imagesWithFilename = $('.note-editable').find('img[data-filename="image.png"]');
    imagesWithFilename.each(function () {
        $(this).remove();
    });

                // Fungsi untuk membersihkan node
                const cleanNode = (node) => {
                    if (node.nodeType === 1) {
                        // Daftar elemen yang gaya (style) nya harus dipertahankan
                        const preserveStylesElements = [
                            'strong', 'b', 'i', 'em', 'u', 'mark', 'del', 's',
                            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'blockquote',
                            'code', 'pre', 'small', 'sup', 'sub', 'a', 'img', 'video', 'audio',
                            'figure', 'figcaption', 'font', 'ul', 'li', 'ol'
                        ]; // Contoh elemen yang dipertahankan
                
                        if (node.tagName.toLowerCase() === 'span') {
                            const parent = node.parentNode;
                            while (node.firstChild) {
                                parent.insertBefore(node.firstChild, node); // Pindahkan semua anak span ke luar
                            }
                            parent.removeChild(node); // Hapus elemen span
                        } else if (!preserveStylesElements.includes(node.tagName.toLowerCase())) {
                            node.removeAttribute('style'); // Hapus style hanya dari elemen lain
                        }
                    }
                };
        
                // Bersihkan semua child nodes
                const cleanChildNodes = (parentNode) => {
                    if (!parentNode) return;
                    const children = Array.from(parentNode.childNodes);
                    children.forEach(child => {
                        cleanNode(child);
                        cleanChildNodes(child); // Bersihkan node anak-anak
                    });
                };
                cleanChildNodes(tempContainer);
        
                // 1. Ganti &nbsp; dengan spasi biasa
                let cleanedHtml = tempContainer.innerHTML.replace(/&nbsp;/g, ' ');
        
                // 2. Ganti <br>, </div>, dan </p> dengan '\n' (newline)
                cleanedHtml = cleanedHtml.replace(/<br\s*\/?>/g, '\n');
                cleanedHtml = cleanedHtml.replace(/<\/(div|p)>/g, '\n');
        
                // Mendapatkan elemen yang sedang dipilih
                const selection = window.getSelection();
                if (!selection.rangeCount) return;
        
                const range = selection.getRangeAt(0);
                const selectedNode = range.startContainer;
        
                // Menambahkan pengecekan untuk memastikan kursor berada di dalam elemen <pre> atau <code>
                if (selectedNode && (selectedNode.nodeName === 'PRE' || selectedNode.nodeName === 'CODE')) {
                    // Sisipkan HTML yang sudah dibersihkan di dalam elemen <pre> atau <code>
                    range.deleteContents();
                    const docFrag = range.createContextualFragment(cleanedHtml);
                    range.insertNode(docFrag);
                    range.setStartAfter(docFrag.lastChild);
                    range.setEndAfter(docFrag.lastChild);
                    selection.removeAllRanges();
                    selection.addRange(range);
                } else {
                    // Jika kursor tidak berada di dalam <pre> atau <code>, paste normal
                    $(this).summernote('pasteHTML', cleanedHtml);
                }
            }
            
               
        }
    });
    $('form').on('submit', function () {
        const editorContent = $('#summernote').summernote('code');
        $('#content').val(editorContent);
    });

    const editor = $('#summernote');
    if (editor.summernote('isEmpty') === false) {
        editor.summernote('placeholder', ''); // Hilangkan placeholder jika sudah ada konten
    }   
});
