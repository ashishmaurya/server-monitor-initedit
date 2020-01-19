<div class="bussiness-content">
    <p class="white">Start Monitoring your server</p>
    <a href="/login" class="btn btn-success btn-lg">
        Get started
    </a>
</div>
<script>
  $(document).ready(function(){
    $("body").attr("id","body").css("backgroud-color","#204d74");
    $("body").addClass("page-bussiness");
    particlesJS('body',{
      "particles": {
        "number": {
          "value": 120,
        },
        "opacity": {
          "value": 0.5,
          "random": true,
          "anim": {
            "enable": false,
            "speed": 1,
            "opacity_min": 0.1,
            "sync": false
          }
        },
        "size": {
          "value": 3,
          "random": true,
          "anim": {
            "enable": false,
            "speed": 40,
            "size_min": 0.1,
            "sync": false
          }
        },
      },
      "interactivity": {
        // "detect_on": "canvas",
        "events": {
          "onhover": {
            "enable": true,
            "mode": "repulse"
          },
          "resize": true
        },
        "modes": {
          "repulse": {
            "distance": 50,
            "duration": 0.4
          },
          "push": {
            "particles_nb": 4
          },
        }
      }
    });

  });
  </script>
