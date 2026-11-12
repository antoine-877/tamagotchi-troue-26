// The pixel icon of each shop item, by name (see DatabaseSeeder).
// An unknown item falls back to the icon of the gauge it fills.
const iconsByName = {
    Bonbon: 'sweet',
    Croquettes: 'kibble',
    Festin: 'feast',
    'Balle rebondissante': 'ball',
    "L'os à mâcher": 'bone',
    'Boisson vitaminée': 'drink',
    'Coussin doré': 'cushion',
};

export function itemIcon(item) {
    return iconsByName[item.name] ?? item.effect;
}
