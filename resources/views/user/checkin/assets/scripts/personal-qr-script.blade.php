<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(generateQRCode, 500);
    });

    function generateQRCode() {
        const qrContainer = document.getElementById('personal-qr-code');
        const qrToken = '{{ $qrToken }}';
        
        if (!qrContainer) {
            return;
        }
        
        if (!qrToken) {
            qrContainer.innerHTML = '<div class="text-danger">QR Token not available</div>';
            return;
        }
        
        qrContainer.innerHTML = '';
        
        try {
            if (typeof QRCode === 'undefined') {
                qrContainer.innerHTML = '<div class="text-danger">QR Code library not loaded. Please refresh the page.</div>';
                return;
            }
            
            new QRCode(qrContainer, {
                text: qrToken,
                width: 200,
                height: 200,
                colorDark: '#000000',
                colorLight: '#FFFFFF',
                correctLevel: QRCode.CorrectLevel.M
            });
            
        } catch (error) {
            qrContainer.innerHTML = '<div class="text-danger">Error generating QR code: ' + error.message + '</div>';
        }
    }

    function downloadQR() {
        const img = document.querySelector('#personal-qr-code img');
        if (img) {
            const link = document.createElement('a');
            link.download = '{{ $user->name }}-{{ $siteSetting->gym_name }}-QR.png';
            link.href = img.src;
            link.click();
        } else {
            toastr.error('QR code not generated yet. Please wait a moment and try again.');
        }
    }

    function printQR() {
        const img = document.querySelector('#personal-qr-code img');
        if (!img) {
            toastr.error('QR code not generated yet. Please wait a moment and try again.');
            return;
        }
        
        const printWindow = window.open('', '_blank');
        const qrImage = img.cloneNode(true);
        
        printWindow.document.write(`
            <html>
                <head>
                    <title>{{ $user->name }} - {{ $siteSetting->gym_name }} QR Code</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            text-align: center; 
                            padding: 20px; 
                            background: #f8f9fa;
                        }
                        .qr-code-wrapper { 
                            display: inline-block; 
                            padding: 20px; 
                            background: white;
                            border-radius: 10px;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                        }
                        .member-info { 
                            margin-top: 20px; 
                            background: white;
                            padding: 20px;
                            border-radius: 10px;
                        }
                        h2, h3 {
                            color: #333;
                        }
                    </style>
                </head>
                <body>
                    <h2>{{ $siteSetting->gym_name }}</h2>
                    <h3>Member QR Code</h3>
                    <div class="qr-code-wrapper">
                        ${qrImage.outerHTML}
                    </div>
                    <div class="member-info">
                        <p><strong>Member:</strong> {{ $user->name }}</p>
                        <p><strong>Generated:</strong> {{ now()->format('M d, Y H:i') }}</p>
                    </div>
                </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.print();
    }
</script>