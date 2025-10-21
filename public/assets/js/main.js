const {
	ClassicEditor,
	Autoformat,
	AutoImage,
	AutoLink,
	Autosave,
	Bold,
	CKBox,
	CKBoxImageEdit,
	CloudServices,
	Code,
	CodeBlock,
	Emoji,
	Essentials,
	GeneralHtmlSupport,
	Heading,
	HtmlComment,
	HtmlEmbed,
	ImageBlock,
	ImageCaption,
	ImageInline,
	ImageInsert,
	ImageInsertViaUrl,
	ImageResize,
	ImageStyle,
	ImageTextAlternative,
	ImageToolbar,
	ImageUpload,
	Italic,
	Link,
	LinkImage,
	List,
	ListProperties,
	Mention,
	Paragraph,
	PasteFromOffice,
	PictureEditing,
	ShowBlocks,
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar,
	TextTransformation
} = window.CKEDITOR;
const { PasteFromOfficeEnhanced, SourceEditingEnhanced } = window.CKEDITOR_PREMIUM_FEATURES;

const LICENSE_KEY =
	'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NDE5OTY3OTksImp0aSI6ImNhZmJmOWZlLTQ3NDktNDNkYi05MWQ0LWFiMTEwZDNmZWVhZCIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6IjAwZGY2ZGU4In0.ps1L2lWsYQgxeeSqE0d6CJIiYBesV0vqyEiY7C04-l9OPyRGISaACEzmxTcf2p91W-sa5dcGieite0TeUtOGCQ';

const CLOUD_SERVICES_TOKEN_URL =
	'https://ma0v7r4o9gx3.cke-cs.com/token/dev/f619d81531b09b3e945f8c49db9621dd530cb5a8f7414e7e8aeef09695be?limit=10';


    
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof dataContentBlog === "undefined") {
            console.error("dataContentBlog is not defined!");
            return;
        }
    
        const editorElement = document.querySelector("#editor"); 
    
        const editorConfig = {
            toolbar: {
                items: [
                    "sourceEditingEnhanced",
                    "showBlocks",
                    "|",
                    "heading",
                    "|",
                    "bold",
                    "italic",
                    "code",
                    "|",
                    "emoji",
                    "link",
                    "insertImage",
                    "ckbox",
                    "insertTable",
                    "codeBlock",
                    "htmlEmbed",
                    "|",
                    "bulletedList",
                    "numberedList"
                ],
                shouldNotGroupWhenFull: false
            },
            plugins: [
                Autoformat,
                AutoImage,
                AutoLink,
                Autosave,
                Bold,
                CKBox,
                CKBoxImageEdit,
                CloudServices,
                Code,
                CodeBlock,
                Emoji,
                Essentials,
                GeneralHtmlSupport,
                Heading,
                HtmlComment,
                HtmlEmbed,
                ImageBlock,
                ImageCaption,
                ImageInline,
                ImageInsert,
                ImageInsertViaUrl,
                ImageResize,
                ImageStyle,
                ImageTextAlternative,
                ImageToolbar,
                ImageUpload,
                Italic,
                Link,
                LinkImage,
                List,
                ListProperties,
                Mention,
                Paragraph,
                PasteFromOffice,
                PasteFromOfficeEnhanced,
                PictureEditing,
                ShowBlocks,
                SourceEditingEnhanced,
                Table,
                TableCaption,
                TableCellProperties,
                TableColumnResize,
                TableProperties,
                TableToolbar,
                TextTransformation
            ],
            cloudServices: {
                tokenUrl: typeof CLOUD_SERVICES_TOKEN_URL !== "undefined" ? CLOUD_SERVICES_TOKEN_URL : "",
            },
            heading: {
                options: [
                    { model: "paragraph", title: "Paragraph", class: "ck-heading_paragraph" },
                    { model: "heading1", view: "h1", title: "Heading 1", class: "ck-heading_heading1" },
                    { model: "heading2", view: "h2", title: "Heading 2", class: "ck-heading_heading2" },
                    { model: "heading3", view: "h3", title: "Heading 3", class: "ck-heading_heading3" },
                    { model: "heading4", view: "h4", title: "Heading 4", class: "ck-heading_heading4" },
                    { model: "heading5", view: "h5", title: "Heading 5", class: "ck-heading_heading5" },
                    { model: "heading6", view: "h6", title: "Heading 6", class: "ck-heading_heading6" }
                ]
            },
            htmlSupport: {
                allow: [{ name: /^.*$/, styles: true, attributes: true, classes: true }]
            },
            image: {
                toolbar: [
                    "toggleImageCaption",
                    "imageTextAlternative",
                    "|",
                    "imageStyle:inline",
                    "imageStyle:wrapText",
                    "imageStyle:breakText",
                    "|",
                    "resizeImage",
                    "|",
                    "ckboxImageEdit"
                ]
            },
            initialData: dataContentBlog || "", // Pakai data atau kosong jika tidak tersedia
            licenseKey: typeof LICENSE_KEY !== "undefined" ? LICENSE_KEY : "",
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: "https://",
                decorators: {
                    toggleDownloadable: {
                        mode: "manual",
                        label: "Downloadable",
                        attributes: { download: "file" }
                    }
                }
            },
            list: {
                properties: { styles: true, startIndex: true, reversed: true }
            },
            mention: {
                feeds: [{ marker: "@", feed: [] }]
            },
            placeholder: "Type or paste your content here!",
            table: {
                contentToolbar: ["tableColumn", "tableRow", "mergeTableCells", "tableProperties", "tableCellProperties"]
            }
        };
    
        ClassicEditor.create(editorElement, editorConfig)
            .then(editor => {
                editor.model.document.on("change:data", () => {
                    editorElement.value = editor.getData();
                });
            })
            .catch(error => console.error(error));
    });
    

configUpdateAlert(editorConfig);

ClassicEditor.create(document.querySelector('#editor'), editorConfig);

/**
 * This function exists to remind you to update the config needed for premium features.
 * The function can be safely removed. Make sure to also remove call to this function when doing so.
 */
function configUpdateAlert(config) {
	if (configUpdateAlert.configUpdateAlertShown) {
		return;
	}

	const isModifiedByUser = (currentValue, forbiddenValue) => {
		if (currentValue === forbiddenValue) {
			return false;
		}

		if (currentValue === undefined) {
			return false;
		}

		return true;
	};

	const valuesToUpdate = [];

	configUpdateAlert.configUpdateAlertShown = true;

	if (!isModifiedByUser(config.licenseKey, '<YOUR_LICENSE_KEY>')) {
		valuesToUpdate.push('LICENSE_KEY');
	}

	if (!isModifiedByUser(config.cloudServices?.tokenUrl, '<YOUR_CLOUD_SERVICES_TOKEN_URL>')) {
		valuesToUpdate.push('CLOUD_SERVICES_TOKEN_URL');
	}

	if (valuesToUpdate.length) {
		window.alert(
			[
				'Please update the following values in your editor config',
				'to receive full access to Premium Features:',
				'',
				...valuesToUpdate.map(value => ` - ${value}`)
			].join('\n')
		);
	}
}
