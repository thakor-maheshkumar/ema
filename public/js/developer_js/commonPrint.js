function printDiv(divID)
  {
            var contents = document.getElementById(divID).innerHTML;
            var frame1 = document.createElement('iframe');
            frame1.name = "frame1";
            frame1.style.position = "absolute";
            frame1.style.top = "-1000000px";
            document.body.appendChild(frame1);
            var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
            frameDoc.document.open();
            frameDoc.document.write('<html><head><title></title>');
            frameDoc.document.write(bootstrap_min);
            frameDoc.document.write(bootstrap_grid);
            frameDoc.document.write('<style>.modal-dialog{margin-bottom:0 !important;} .my-3 {margin-bottom:0 !important; }.mb-1 {marign-bottom:0 !important} div.printBorder {float:left; position: fixed; top:0; left:0; right:0; bottom: 0; border:5px solid #ccc; z-indx:77777;}.modal-content .modal-header h2 {margin: 6px 0;}.modal-content .modal-header {padding: 0 20px;}.modal-body{padding-bottom:0;}.form-group {margin-bottom: 10px;}.modal {height: auto;overflow-x: visible;overflow-y: visible;}.homeModel {max-width: 530px;margin: 1.75rem auto;padding:0 0 20px 0; }.modal{position: relative;}h2 {font-size: 18px;color: #000000;font-weight: 300;font-style: normal;}.modal-content .close {display:none;}.btn {display:none;}.justify-content-xl-center {-ms-flex-pack: center !important;justify-content: center !important;}.pl-3, .px-3 {padding-left: 1rem !important;}.row {display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -15px;margin-left: -15px;}.form-row {display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -5px;margin-left: -5px;}.form-row>.col, .form-row>[class*=col-] {padding-right: 10px;padding-left: 10px;}.col-xl-12 {-ms-flex: 0 0 100%;flex: 0 0 100%;max-width: 100%;}.col-lg-6 {-ms-flex: 0 0 50%;flex: 0 0 50%;max-width: 50%;}.mb-2, .my-2 {margin-bottom: 0.5rem !important;}.col-xl-4 { -ms-flex: 0 0 33.333333%; flex: 0 0 33.333333%; max-width: 33.333333%;}</style>');
            frameDoc.document.write('</head><body><div class="printBorder"></div>');
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                document.body.removeChild(frame1);
            }, 500);
            return false;

  }