var canvas = document.getElementById('qrCanvas');
var myAddress ="14AnA29UZVJbcCJjVBqGPLUgmUcLzKgfYL";
var CheckoutAmount = 0.00;
var GuestName = "user";
var receipt_memo = "Bitcoin Payment Receipt Number : 123";
function OnClickGenOR(amount){
    generateQR(encodeBip21Uri(myAddress, amount, GuestName, receipt_memo),canvas);
    console.log(amount);

}

//var lastResponse; //stores the last response from wherever
function encodeBip21Uri(address, amount, name, receipt){
    var encodedUri = 'bitcoin:' + address + '?amount='+ amount + '&label=' + name + '&message=' + receipt;
    console.log(encodedUri);
    return encodedUri;
}

// Generate the QR Code with Nayuki's qrcodegen library (see QR-Code-generator folder)
//from https://github.com/nayuki/QR-Code-generator
function generateQR(message, canvas) {
    var QRC = qrcodegen.QrCode;
    var scale = 8;
    var border = 4;
    var qr0 = QRC.encodeText(message, QRC.Ecc.HIGH);
    qr0.drawCanvas(scale, border, canvas);
}




