@once
    <script>
        function printPharmacyReceipt(html) {
            const receiptWindow = window.open('', '_blank', 'width=360,height=640');
            if (!receiptWindow) {
                alert('Please allow popups to print the pharmacy receipt.');
                return;
            }

            receiptWindow.document.write(`<!doctype html><html><head><meta charset="utf-8"><title>Pharmacy Receipt</title>
                <style>
                    @page { margin: 0; }
                    * { box-sizing: border-box; }
                    html, body { margin: 0; padding: 0; height: auto; min-height: 0; }
                    body { width: 80mm; padding: 2mm 4mm; color: #000; font-family: monospace; font-size: 12px; line-height: 1.25; }
                    .thermal-receipt { width: 72mm; max-width: 100%; overflow-wrap: anywhere; }
                    h5 { font-size: 14px; margin: 0 0 3px; }
                    p { margin: 0 0 3px; }
                    table { width: 100%; border-collapse: collapse; }
                    td { padding: 2px 0; border: 0; vertical-align: top; }
                    tr { break-inside: avoid; }
                    .divider { border-top: 1px dashed #000; margin: 5px 0; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .fw-bold { font-weight: 700; }
                    .small { font-size: 11px; }
                </style></head><body>${html}</body></html>`);
            receiptWindow.document.close();
            receiptWindow.focus();

            setTimeout(function () {
                // Measure the content, not the popup viewport, to avoid feeding a blank page tail.
                const heightMm = Math.ceil(receiptWindow.document.body.getBoundingClientRect().height * 25.4 / 96) + 1;
                const paperStyle = receiptWindow.document.createElement('style');
                paperStyle.textContent = '@page { size: 80mm ' + heightMm + 'mm; margin: 0; }';
                receiptWindow.document.head.appendChild(paperStyle);
                receiptWindow.print();
                receiptWindow.close();
            }, 300);
        }
    </script>
@endonce
