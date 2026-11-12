// Turns a creature's gauges into what the screen shows: sprite state,
// background fill, a mood word and two French sentences.
// Pure functions: same gauges in, same mood out.

// Under these values a gauge is « low » (blinking icon, rose card).
export const lowThresholds = { food: 30, mood: 30, energy: 25 };

export function isLow(gauge, value) {
    return value < lowThresholds[gauge];
}

export function moodOf(creature) {
    const { name, food, mood, energy } = creature;

    if (energy < lowThresholds.energy) {
        return {
            state: 'sleepy',
            tone: 'aqua',
            word: 'fatiguée',
            headline: `${name} tombe de sommeil. Un dodo ?`,
            status: `${name} bâille à s'en décrocher la mâchoire.`,
        };
    }

    if (food < lowThresholds.food) {
        return {
            state: 'sad',
            tone: 'lilac',
            word: 'affamée',
            headline: `${name} a faim. Un petit quelque chose ?`,
            status: `${name} fixe sa gamelle vide.`,
        };
    }

    if (mood < lowThresholds.mood) {
        return {
            state: 'sad',
            tone: 'lilac',
            word: 'triste',
            headline: `${name} s'ennuie. Une partie ?`,
            status: `${name} soupire dans son coin.`,
        };
    }

    if (mood >= 70) {
        return {
            state: 'happy',
            tone: 'acid',
            word: 'joyeuse',
            headline: `${name} est en pleine forme.`,
            status: `${name} sautille sur place.`,
        };
    }

    return {
        state: 'idle',
        tone: 'lilac',
        word: 'calme',
        headline: `${name} attend un geste de votre part.`,
        status: `${name} vous regarde.`,
    };
}

// The short reaction shown right after a successful action.
export function reactionOf(action, creature) {
    const { name } = creature;

    if (action === 'sleep') {
        return {
            state: 'sleepy',
            tone: 'aqua',
            word: 'endormie',
            headline: 'Bonne sieste. On baisse la lumière.',
            status: `Chut… ${name} dort.`,
        };
    }

    return {
        state: 'happy',
        tone: 'acid',
        word: 'joueuse',
        headline: `Attrape ! ${name} ne tient plus en place.`,
        status: `${name} bondit après la balle.`,
    };
}
