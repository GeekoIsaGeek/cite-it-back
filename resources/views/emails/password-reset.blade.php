<!DOCTYPE html>
<html>
    <head>
       <style>
        *{
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color:white;
        }
        a{
            text-decoration: none;
        }
       </style>
    </head>
    <body style="background-color: #181623; padding-top:80px; padding-bottom:110px;">
        <center style="padding-bottom:23px;">
            <img src="https://firebasestorage.googleapis.com/v0/b/image-gallery-610ea.appspot.com/o/users%2FGeorge-VCXtOV23v8bv667J0SkSr8OiQmm2%2Fgallery%2FVector.png?alt=media&token=f227fa1d-618b-422d-8071-df9130195b0f" alt="quotes">
            <h4 style="color: #DDCCAA; margin-top:9px;">MOVIE QUOTES</h4>
        </center>
        <div style="padding:0 194px 0 194px;">
            <p>Hello, {{ $user['username']}}!</p>
            <p style="margin-bottom:32px;">Thanks for being a member of Movie quotes community! We really appreciate it. Please click the button below to reset your password:</p>
            <a href="{{$url}}" style="padding: 7px 13px; background-color:red; border-radius:4px; cursor:pointer; color:white;">Reset Password</a>
            <p style="margin-top:40px;">If clicking doesn't work, you can try copying and pasting it to your browser:</p>
            <a href="{{$url}}" style="color:#DDCCAA; max-width:300px; word-wrap:break-word">{{$url}}</a>
            <p >If you have any problems, please contact us: support@example.ge</p>
            <p>MovieQuotes Crew</p>
        </div>
    </body>
</html>