import { FilterInterface } from "@/app/(admin-routes)/home/page";
import { Input, Select, SelectItem } from "@nextui-org/react";
import { CheckboxArray } from "./Preference";

type FilterProps = {
    filters: FilterInterface,
    updateFilters: (filter: FilterInterface) => void,
    languages: CheckboxArray[],
    categories: CheckboxArray[],
    sources: CheckboxArray[],
};

const Filter: React.FC<FilterProps> = ({ filters, updateFilters, languages, categories, sources }) => {
    return (
        <div className="flex justify-between items-start mt-8 mb-4 flex-wrap gap-4 h-fit">
            <Input
                type="text"
                label="Search Article by Title"
                labelPlacement="inside"
                className="w-full md:w-52 mt-2 gap-1"
                onChange={(e) => updateFilters({...filters, search: e.target.value})}
            />

            <div className="flex gap-8 md:gap-6 flex-wrap w-full md:w-fit mt-2">
                <Input
                    type="date"
                    className="w-full md:w-52 h-fit !mt-0"
                    onChange={(e) => updateFilters({...filters, date: e.target.value})}
                    labelPlacement="inside"
                />

                <Select
                    label="Language"
                    className="w-full md:w-52 !mt-0" 
                    labelPlacement="inside"
                    onChange={(e) => updateFilters({...filters, language: e.target.value})}
                >
                    {languages.map((language) => (
                        <SelectItem key={language.value} value={language.value}>
                            {language.label}
                        </SelectItem>
                    ))}
                </Select>

                <Select
                    label="Category"
                    className="w-full md:w-52 !mt-0" 
                    labelPlacement="inside"
                    onChange={(e) => updateFilters({...filters, category: e.target.value})}
                >
                    {categories.map((category) => (
                        <SelectItem key={category.value} value={category.value}>
                            {category.label}
                        </SelectItem>
                    ))}
                </Select>

                <Select
                    label="Source"
                    className="w-full md:w-52 !mt-0" 
                    labelPlacement="inside"
                    onChange={(e) => updateFilters({...filters, source: e.target.value})}
                >
                    {sources.map((source) => (
                        <SelectItem key={source.value} value={source.value}>
                            {source.label}
                        </SelectItem>
                    ))}
                </Select>
            </div>
        </div>
    )
}
export default Filter;