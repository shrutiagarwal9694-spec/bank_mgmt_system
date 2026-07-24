<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/webp" href="ams_Bank.webp">
<meta charset="UTF-8" />
<title>AMS Bank</title>
<script>
    // Disable right-click
    document.addEventListener('contextmenu', event => event.preventDefault());

    // Disable certain keypresses (F12, Ctrl+U, etc.)
    document.addEventListener('keydown', function (event) {
        if (
            event.key === "F12" ||
            (event.ctrlKey && event.key.toLowerCase() === "u") ||
            (event.ctrlKey && event.key.toLowerCase() === "s") ||
            (event.ctrlKey && event.key.toLowerCase() === "p") ||
            event.key === "Tab"
        ) {
            event.preventDefault();
        }
    });

    // Prevent mousewheel zoom
    window.addEventListener('wheel', function(e) {
        if (e.ctrlKey) e.preventDefault();
    }, { passive: false });
</script>
<style>
  body {
    margin: 0;
    height: 100vh;
    background: url('index-bg.png') no-repeat center center/cover;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  img {
    margin-top: 20%;
    border-radius: 50%;
    border: 5px solid #ffffff;
    transition: box-shadow 0.3s ease;
    cursor: pointer;
  }
  img:hover {
    box-shadow:
      0 0 20px 10px #bde0fe,
      0 0 15px 7px #219ebc,
      0 0 10px 5px #0077b6,
      0 0 8px 3px #03045e;
  }
</style>
</head>
<body>
  <a href="https://kshatriya.in.net/Shruti_Agarwal/login.php">
    <img src="ams-icon.png" width="380" alt="AMS Bank Icon" />
  </a>
</body>
</html>
