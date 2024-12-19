import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs) {
	return twMerge(clsx(inputs));
}

export const plural = (count, noun, suffix = 's') => `${count} ${noun}${count !== 1 ? suffix : ''}`;
export const needsBreakAll = (str, len) => {
	return str.split(' ').some((w) => {
		return w.length >= len;
	});
};
export const toggleAccordions = (htmlClass) => {
	const accordions = document.querySelectorAll(htmlClass);
	const newState = Array.from(accordions).some((acc) => acc.dataset.state === 'closed') ? 'open' : 'closed';
	accordions.forEach((element) => {
		if (element.dataset.state !== newState) element.click();
	});
};

export const dateTimeOptions = {
	weekday: 'short',
	year: 'numeric',
	month: 'short',
	day: 'numeric',
	hour: 'numeric',
	minute: 'numeric',
	timeZoneName: 'short',
};
