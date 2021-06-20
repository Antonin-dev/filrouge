const quantityInput = document.querySelector("#reservation_quantity");
const quantityDisplay = document.querySelector(".quantity-ajax");
const priceReservationDisplay = document.querySelector(".total-ajax");
const priceDisplay = document.querySelector(".price-ajax");

document.addEventListener("DOMContentLoaded", async () => {
  quantityDisplay.textContent = quantityInput.value;
  priceDisplay.textContent = await fetchSpe();
  priceReservationDisplay.textContent = await fetchSpe();
});

const updateValue = async () => {
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
quantityInput.addEventListener("change", updateValue);
priceDisplayFunction();
