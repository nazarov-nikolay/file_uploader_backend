//todo rework to angular worker
function parseFile(file, callback) {
    var fileSize = file.size;
    var chunkSize = 1405092; // 2 mb minus 33% cause of base64
    var offset = 0;
    var block = null;
    var r = null;

    var foo = function(evt) {
        var binary = "";
        if (evt.target.error == null) {
            offset += evt.loaded;
            r = new Uint8Array(evt.target.result);
            for (var i = 0, l = r.length; i < l; i++) {
                binary += String.fromCharCode(r[i]);
            }
            callback([false, offset, binary]);
        } else {
            console.log("Read error: " + evt.target.error);
            callback([true, '', '']);
            return;
        }

        if (offset >= fileSize) {
            callback([true, '', '']);
            return;
        }

        block(offset, chunkSize, file);
    };

    block = function(_offset, length, _file) {
        var r = new FileReader();
        var blob = _file.slice(_offset, length + _offset);
        r.onload = foo;
        r.readAsArrayBuffer(blob);
    };

    block(offset, chunkSize, file);
}

onmessage = function(e) {
    parseFile(e.data, function(evt) {
        postMessage([evt[0], evt[1], evt[2]]);
    });
};