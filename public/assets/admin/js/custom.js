function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('URL copied to clipboard!');
    }, function(err) {
        toastr.error('Could not copy text: ', err);
    });
}