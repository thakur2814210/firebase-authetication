/*var upLoadLimiter = function (sizeLimit, inputSelector) {

    //limit size of image upload
    var inputEl = jQuery(inputSelector);
    console.log(inputEl);
    console.log(inputEl.prop('files'));
    var size = inputEl.prop('files')[0].size;
    var testSize = size / 1024;
    var options = { title: "Upload unsuccessful", message: "Please upload a file not larger than" + " " + sizeLimit + "KB"};
    if (testSize > sizeLimit) {
        showModalWithTitleAndMessage(options);
        return false; 
    }
    return true;
};*/
