function cpm_upload_image(api_key,html_id,field_name) {
    var string = api_key;

    string = string.substring(0, string.length - 2);
    // console.log(html_id);
    var array_id = [];
    const client = filestack.init(string);
    const options = {
        fromSources: ['local_file_system', 'googledrive', 'unsplash', 'facebook', 'instagram'],
        maxFiles: 1,
		lang: 'pt',
        uploadInBackground: false,
        onUploadDone: (res) => {
            const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
            const validZipTypes = ['application/x-zip-compressed'];

            for (let i = 0; i < res.filesUploaded.length; i++) {
                array_id[i] = res.filesUploaded[i].url;
                if (validZipTypes.includes(res.filesUploaded[i].mimetype)) {
                    jQuery("#cpm_formidable_img_"+html_id).append(" <figure> <img src='../wp-content/plugins/cpm-formidable-form-addon/archive.png' alt='archive.png'> <figcaption>" + res.filesUploaded[i].filename + "</figcaption> </figure> ");
                } else if (validImageTypes.includes(res.filesUploaded[i].mimetype)) {
                    jQuery("#cpm_formidable_img_"+html_id).append("<figure> <img src='" + array_id[i] + "' alt='file.png'> <figcaption>" + res.filesUploaded[i].filename + "</figcaption> </figure>");
                } else {
                    jQuery("#cpm_formidable_img_"+html_id).append("<figure> <img src='../wp-content/plugins/cpm-formidable-form-addon/all.png' alt='file.png'> <figcaption>" + res.filesUploaded[i].filename + "</figcaption> </figure>");
                }
            }
            
            document.getElementById(field_name).value = JSON.stringify(array_id);
        }
    };
    const picker = client.picker(options);
    picker.open();
}
