
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
         <style>
            *{
                font-family: Calibri, Arial, Helvetica, sans-serif;
            }

           body{
               padding:1 0.5cm;
               font-size: 12pt;
               color:#575757;
           }
           @page { 
                size:8.5in 11in; 
                margin:0.3in 0.7in;
           }
             h2,h3,p.nomarg{
                 margin:0;
             }
            
            table{
                width:7.1in;
                margin: 0 auto;
                border-collapse: collapse;
            }
            
            td{
                vertical-align:middle;
            }
            
             .logo{
                 width:8cm;
             }
            .mayusculas{
                text-transform: uppercase
            }
             hr{
                 border-top: solid 1px #919191;
                 border-bottom: none;
                 margin-top:0.7cm;
             }
        
        </style>
        
        <p>Enhorabuena !!!! <br>
            Correo: {{ $correo }}  <br>
        </p>

        <p>
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
        </p>

        <p align="justify" style="margin: 6em 0; color:black; font-size:1.0em ">Esta comunicación por correo electrónico contiene información propietaria y confidencial con derecho a protección y/o exenta de divulgación bajo la ley aplicable y se puede privilegiar legalmente. Es para uso del (de los) receptor(es) pretendido(s). Si usted no es el receptor objetivo, favor de eliminar esta comunicación por correo electrónico (incluyendo cualquier archivo adjunto) y las copias impresas de inmediato y notifique al emisor de inmediato. Cualquier uso o diseminación no autorizados de esta comunicación por correo electrónico (incluyendo cualquier archivo adjunto) en cualquier forma, en su totalidad o en parte, queda estrictamente prohibido. </p>
    </body>
</html>
