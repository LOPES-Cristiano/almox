<div id="globalModal" class="modal">
  <div class="modal-content">
    <span class="close" id="globalModalClose">&times;</span>
    <div class="modal-icon" id="modalIconContainer"></div>
    <p id="globalModalMessage"></p>
  </div>
</div>

<div id="loading-spinner">
  <span class="loader"></span>
</div>

<style>
  .modal {
    display: none;
    position: fixed;
    z-index: 9999;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(3px);
  }

  .modal-content {
    background: white;
    color: #333;
    margin: 15% auto;
    padding: 30px 40px;
    border-radius: 12px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
  }

  .modal-content.success {
    border-top: 6px solid #2196f3;
  }

  .modal-content.error {
    border-top: 6px solid #f44336;
  }

  .modal-icon svg {
    width: 48px;
    height: 48px;
    margin-bottom: 20px;
  }

  .modal p {
    font-size: 1.1rem;
    margin: 0;
  }

  .close {
    position: absolute;
    top: 10px;
    right: 14px;
    font-size: 28px;
    color: #888;
    cursor: pointer;
    transition: 0.2s;
  }

  .close:hover {
    color: #333;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .loader {
    width: 48px;
    height: 48px;
    border: 3px dotted #000000;
    border-style: solid solid dotted dotted;
    border-radius: 50%;
    display: inline-block;
    position: relative;
    box-sizing: border-box;
    animation: rotation 2s linear infinite;
  }

  .loader::after {
    content: "";
    box-sizing: border-box;
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    border: 3px dotted #ffd700;
    border-style: solid solid dotted;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    animation: rotationBack 1s linear infinite;
    transform-origin: center center;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

@keyframes rotationBack {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(-360deg);
    }
}


#loading-spinner {
    position: fixed;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.6);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
    z-index: 9999;
    visibility: hidden; 
}
</style>

<script>
  window.onload = function () {
    const erro = <?= json_encode(session()->getFlashdata('erro')) ?>;
    const sucesso = <?= json_encode(session()->getFlashdata('sucesso')) ?>;

    const modal = document.getElementById('globalModal');
    const message = document.getElementById('globalModalMessage');
    const closeBtn = document.getElementById('globalModalClose');
    const modalContent = document.querySelector('.modal-content');
    const modalIcon = document.getElementById('modalIconContainer');

    if (erro || sucesso) {
      message.textContent = erro || sucesso;
      modalContent.classList.remove('success', 'error');

      if (erro) {
        modalContent.classList.add('error');
        modalIcon.innerHTML = `
          <svg viewBox="0 0 24 24" fill="#f44336">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10
            10-4.48 10-10S17.52 2 12 2zm5 13l-1.41 
            1.41L12 13.41l-3.59 3.59L7 15l3.59-3.59L7 
            7.83 8.41 6.41 12 10.59l3.59-3.59L17 
            7.83l-3.59 3.59L17 15z"/>
          </svg>
        `;
      } else {
        modalContent.classList.add('success');
        modalIcon.innerHTML = `
          <svg viewBox="0 0 24 24" fill="#2196f3">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 
            19 21 7l-1.41-1.41z"/>
          </svg>
        `;
      }

      modal.style.display = 'block';

      closeBtn.onclick = function () {
        modal.style.display = 'none';
      };

      window.onclick = function (event) {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      };
    }
  };

  let loadingTimeout;
  let maxDurationTimeout;

  function showLoadingSpinner() {
    document.getElementById('loading-spinner').style.visibility = 'visible';
    maxDurationTimeout = setTimeout(hideLoadingSpinner, 3000);
  }

  function hideLoadingSpinner() {
    clearTimeout(maxDurationTimeout);
    document.getElementById('loading-spinner').style.visibility = 'hidden';
  }

  window.addEventListener("beforeunload", function () {
    loadingTimeout = setTimeout(showLoadingSpinner, 50);
  });

  window.addEventListener("load", function () {
    clearTimeout(loadingTimeout);
    hideLoadingSpinner();
  });
</script>
