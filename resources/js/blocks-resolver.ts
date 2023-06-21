/**
 * Blocks resolver
 */

// @ts-ignore
import type { ComponentResolver} from "unplugin-vue-components/dist";

export interface BlocksResolverOptions {

}

export function BlocksResolver(options: BlocksResolverOptions = {}): ComponentResolver {
    return {
        type: 'component',
        resolve: (name: string) => {
            if (/^B[A-Z]/.test(name)) {
                const compName = 'b-' + name.slice(1).toLowerCase();
                return {
                    from: `@/views/blocks/${compName}.vue`,
                }
            }
        },
    }
}
