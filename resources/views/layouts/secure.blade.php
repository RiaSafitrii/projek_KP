<style>
  body {
    -webkit-user-select: none; /* Safari */
    -moz-user-select: none;    /* Firefox */
    -ms-user-select: none;     /* IE10+/Edge */
    user-select: none;         /* Standard */
  }
</style>

<script>
  // Blok klik kanan
  document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
  });

  // Blok tombol F12, Ctrl+U, dll
  document.onkeydown = function(e) {
    if (
      e.keyCode == 123 || // F12
      (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) || // Ctrl+Shift+I
      (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) || // Ctrl+U
      (e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) // Ctrl+S
    ) {
      return false;
    }
  };
</script>

<style>
  #screenshot-warning {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.9);
    color: white;
    font-size: 24px;
    text-align: center;
    padding-top: 20%;
    z-index: 999999; /* pastikan di atas semua */
  }
</style>

<div id="screenshot-warning">🚫 Screenshot tidak diperbolehkan!</div>

<script>
  function showWarning() {
    const el = document.getElementById("screenshot-warning");
    if (!el) return;
    el.style.display = "block";
    setTimeout(() => {
      el.style.display = "none";
    }, 3000);
  }

  // Cegah tombol Print Screen (PC)
  document.addEventListener("keyup", function (e) {
    if (e.key === "PrintScreen") {
      showWarning();
    }
  });


</script>
