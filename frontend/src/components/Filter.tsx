import { ArticleInterface, FilterInterface } from "@/app/(admin-routes)/home/page";
import { Input, Select, SelectItem } from "@nextui-org/react";
import { CheckboxArray } from "./Preference";

type FilterProps = {
    filters: FilterInterface,
    updateFilters: (filter: FilterInterface) => void,
    languages: CheckboxArray[],
};

const Filter: React.FC<FilterProps> = ({ filters, updateFilters, languages }) => {
    return (
        <div className="flex justify-between items-start mt-8 mb-4 flex-wrap gap-4 h-fit">
            <Input
                type="text"
                label="Search Article by Date"
                labelPlacement="outside"
                className="w-fit !mt-0"
                onChange={(e) => updateFilters({...filters, search: e.target.value})}
                variant="bordered"
            />

            <div className="flex gap-8 sm:gap-6 flex-wrap">
                <Input
                    type="date"
                    className="w-fit h-fit !mt-0"
                    onChange={(e) => updateFilters({...filters, date: e.target.value})}
                    variant="bordered"
                    labelPlacement="outside"
                />

                <Select
                    label="Language"
                    className="w-52 !mt-0" 
                    labelPlacement="outside"
                    onChange={(e: any) => updateFilters({...filters, language: e})}
                >
                    {languages.map((language) => (
                        <SelectItem key={language.value} value={language.value}>
                            {language.label}
                        </SelectItem>
                    ))}
                </Select>
            </div>
        </div>
    )
}
export default Filter;