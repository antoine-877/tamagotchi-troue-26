import type { VariantProps } from "class-variance-authority"
import { cva } from "class-variance-authority"

export { default as Button } from "./Button.vue"

// « Planche-contact »: ink rim, hard offset shadow, the button sinks when pressed.
const raised = "border-2 border-border shadow-hard-sm hover:-translate-x-px hover:-translate-y-px hover:shadow-hard active:translate-x-[3px] active:translate-y-[3px] active:shadow-none"

export const buttonVariants = cva(
  "inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-none text-[15px] font-extrabold transition-[transform,box-shadow,background-color] duration-100 motion-reduce:transition-none disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 aria-invalid:border-destructive",
  {
    variants: {
      variant: {
        default:
          `bg-primary text-primary-foreground ${raised}`,
        destructive:
          `bg-destructive text-paper dark:text-ink ${raised}`,
        outline:
          `bg-background hover:bg-accent hover:text-accent-foreground ${raised}`,
        secondary:
          `bg-secondary text-secondary-foreground ${raised}`,
        ghost:
          "hover:bg-accent hover:text-accent-foreground",
        link: "text-link underline underline-offset-4 decoration-2 hover:decoration-4",
      },
      size: {
        "default": "h-11 px-5 has-[>svg]:px-4",
        "sm": "h-9 gap-1.5 px-3 has-[>svg]:px-2.5",
        "lg": "h-12 px-7 text-base has-[>svg]:px-5",
        "icon": "size-11",
        "icon-sm": "size-9",
        "icon-lg": "size-12",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  },
)
export type ButtonVariants = VariantProps<typeof buttonVariants>
