const quantityInput = document.querySelector("#reservation_quantity");
const quantityDisplay = document.querySelector(".quantity-ajax");
const priceReservationDisplay = document.querySelector(".total-ajax");
const priceDisplay = document.querySelector(".price-ajax");

document.addEventListener("DOMContentLoaded", async () => {
  // Quand le dom est chargé j'effectue l'affichage dans le simulateur
  quantityDisplay.textContent = quantityInput.value;
  // Je récupere le prix d'une entre via une fonction asynchrone
  priceDisplay.textContent = await fetchSpe();
  // Je l'affiche dans la div
  priceReservationDisplay.textContent = await fetchSpe();
});

const updateValue = async () => {
  // Je récupere le prix de l'entrée au parc et j'effectue le calcul en fonction de la quantité choisis
  let priceasync =
    Math.round((await fetchSpe()) * quantityInput.value * 100) / 100;
  quantityDisplay.textContent = quantityInput.value;
  priceReservationDisplay.textContent = priceasync;
};
const fetchSpe = async () => {
  const result = await fetch("/api/price");
  const resultJson = await result.json();
  const price = resultJson.price;
  return price;
};
// Quand la valeur est modifié dans l'input j'effectue la fonction updateValue
quantityInput.addEventListener("change", updateValue);
priceDisplayFunction();
