<?php
require_once plugin_dir_path(__FILE__) . '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

function cda_generate_certificate_number() {
  return strtoupper(uniqid('CERT-'));
}

function cda_generate_certificate($name, $certificate_number) {
  $css = file_get_contents(plugin_dir_path(__FILE__) . '../css/certificate-style.css');

  // Correcting image URL
  $image_url = plugins_url('images/certificate.jpg', __FILE__);

  $html = '
  <html>
  <head>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap");
      
      body {
        margin: 0;
        padding: 0;
        width: 210mm;
        height: 297mm;
      }

      .cert {
        position: relative;
        padding: 0;
        margin: 0;
      }

      .pname {
        position: absolute;
        font-family: "Dancing Script", cursive; /* Updated font-family */
        font-size: 3rem;
        text-align: center;
        z-index: 1;
        width: 100%;
        height: 297mm;
        top: 39%;
        margin-left: -5%;
      }

      #cert {
        width: 100%;
        height: 100%;
        position: absolute;
        z-index: 0;
        margin: -5% 0 0 -5%;
      }

      .certNo {
        position: absolute;
        font-size: medium;
        font-weight: bold;
        top: 90%;
        left: 10%;
        
      }
    </style>
  </head>
  <body>
    <div class="cert">
      <div class="pname">' . htmlspecialchars($name) . '</div>
      <img id="cert" src="https://ik.imagekit.io/s3jkgwyie/Green%20Growth%20Africa%20Files/Green%20Growth%20Workshop%20Certificate.jpg?updatedAt=1717515172497">
      <div class="certNo">' . htmlspecialchars($certificate_number) .'</div>
    </div>
  </body>
  </html>';

  $options = new Options();
  $options->set('isHtml5ParserEnabled', true);
  $options->set('isRemoteEnabled', true);
  
  $dompdf = new Dompdf($options);
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();

  $output = $dompdf->output();
  $upload_dir = wp_upload_dir();
  $file_path = $upload_dir['basedir'] . '/certificate-' . $certificate_number . '.pdf';
  file_put_contents($file_path, $output);

  return $upload_dir['baseurl'] . '/certificate-' . $certificate_number . '.pdf';
}
?>
