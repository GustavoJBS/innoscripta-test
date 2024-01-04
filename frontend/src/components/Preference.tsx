import { Accordion, AccordionItem, Card, CardBody, Checkbox, CheckboxGroup, Tab, Tabs } from "@nextui-org/react";

export interface PreferenceInterface {
    id: number,
    user_id: number,
    languages: Array<string>,
    sources: Array<string>,
    categories: Array<string>,
    created_at: Date,
    updated_at: Date,
}

export interface CheckboxArray {
    value: string | number
    label: string | number
}
interface PreferenceComponent {
    preference: PreferenceInterface,
    setPreference: (preference: PreferenceInterface) => void,
    languages: CheckboxArray[],
    sources: CheckboxArray[],
    categories: CheckboxArray[],
    updatePreference: (preference: PreferenceInterface) => void
}
const Preference: React.FC<PreferenceComponent> = ({ preference, setPreference, updatePreference, languages, sources, categories }) => {
    function setPreferenceProperty(preference: PreferenceInterface) {
        setPreference(preference);

        updatePreference(preference);
    }

    return (
        <div className="my-6">
            <Accordion variant="splitted">
                <AccordionItem key="1" aria-label="Accordion Preferences" title="User Preferences">
                    <Tabs aria-label="Options" size="sm">
                        <Tab key="languages" title="Languages">
                            <Card>
                                <CardBody>
                                    <CheckboxGroup
                                        label="Select Your Language"
                                        value={preference.languages}
                                        size="sm"
                                        onChange={(e: any) => setPreferenceProperty({ ...preference, languages: e })}
                                    >
                                        <div className="grid gap-2 grid-cols-2 md:grid-cols-3">
                                            {
                                                languages.map((language: CheckboxArray) => (
                                                    <Checkbox
                                                        key={language.value}
                                                        value={String(language.value)}
                                                        checked={preference.languages.includes(String(language.value))}
                                                    >
                                                        {language.label}
                                                    </Checkbox>
                                                ))
                                            }
                                        </div>
                                    </CheckboxGroup>
                                </CardBody>
                            </Card>
                        </Tab>

                        <Tab key="categories" title="Categories">
                            <Card>
                                <CardBody>
                                    <CheckboxGroup
                                        label="Select Best Categories"
                                        value={preference.categories.map((value) => String(value))}
                                        size="sm"
                                        onChange={(e: any) => setPreferenceProperty({ ...preference, categories: e })}
                                    >
                                        <div className="grid gap-2 grid-cols-2 md:grid-cols-3">
                                            {categories.map((category: CheckboxArray) => (
                                                <Checkbox
                                                    key={category.value}
                                                    value={String(category.value)}
                                                    checked={preference.categories.includes(String(category.value))}
                                                >
                                                    {category.label}
                                                </Checkbox>
                                            ))}
                                        </div>
                                    </CheckboxGroup>
                                </CardBody>
                            </Card>
                        </Tab>

                        <Tab key="sources" title="Sources">
                            <Card>
                                <CardBody>
                                    <CheckboxGroup
                                        label="Select Best Sources"
                                        value={preference.sources.map((value) => String(value))}
                                        size="sm"
                                        onChange={(e: any) => setPreferenceProperty({ ...preference, sources: e })}
                                    >
                                        <div className="grid gap-2 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
                                            {sources.map((source: CheckboxArray) => (
                                                <Checkbox
                                                    key={source.value}
                                                    value={String(source.value)}
                                                    checked={preference.sources.includes(String(source.value))}
                                                >
                                                    {source.label}
                                                </Checkbox>
                                            ))}
                                        </div>
                                    </CheckboxGroup>
                                </CardBody>
                            </Card>
                        </Tab>
                    </Tabs>
                </AccordionItem>
            </Accordion>
        </div>
    )
}

export default Preference;