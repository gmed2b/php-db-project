// Création du graphique
window.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("activitesChart").getContext("2d");
  new Chart(ctx, configActivites);
});

// Données factices pour le graphique
const dataActivites = {
  labels: ["Sport", "Culture", "Loisirs"],
  datasets: [
    {
      label: "Nombre d'activités",
      data: [20, 15, 10],
      backgroundColor: [
        "rgba(54, 162, 235, 0.5)",
        "rgba(255, 99, 132, 0.5)",
        "rgba(75, 192, 192, 0.5)",
      ],
      borderColor: [
        "rgba(54, 162, 235, 1)",
        "rgba(255, 99, 132, 1)",
        "rgba(75, 192, 192, 1)",
      ],
      borderWidth: 1,
    },
  ],
};

// Configuration du graphique
const configActivites = {
  type: "bar",
  data: dataActivites,
  options: {
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  },
};
